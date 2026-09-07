<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use Stenope\Bundle\Behaviour\HtmlCrawlerManagerInterface;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;

/**
 * Transforme les citations du mode entretien en remises en contexte et en prises de
 * parole nommées.
 *
 * Le processor ne travaille que sur la propriété du mode entretien, remplie par
 * {@see ArticleReadingModesProcessor} : les citations d'un autre mode, ou d'un article
 * ordinaire, ne le rencontrent jamais. C'est ce qui réserve mécaniquement ces blocs au
 * mode entretien, sans marqueur supplémentaire à la rédaction.
 *
 * ## Ce que la rédaction écrit
 *
 * Deux blockquotes adjacents fusionnent en un seul — comportement de Parsedown, pas un
 * choix. Plutôt que d'imposer un séparateur, ce processor **re-découpe** chaque citation :
 * les paragraphes de tête sans nom forment la remise en contexte, et chaque nom rencontré
 * ouvre une prise de parole qui absorbe les paragraphes suivants non attribués. Blocs
 * collés ou séparés produisent donc le même rendu.
 *
 * Deux écritures du nom sont acceptées, et normalisées vers la même sortie :
 *
 * ```markdown
 * > **Eva :** La question.        ouvre une prise de parole
 * > Une relance.
 * > <cite>Maxime</cite>           la clôt — forme déjà enseignée par le guide de style
 * ```
 *
 * ## Ce qui sort
 *
 * `<div class="interview-turn">` avec le nom en `<p class="interview-turn__speaker">`,
 * texte simple : ni `<cite>`, ni `<blockquote>`. Le nom d'un intervenant est une étiquette
 * de locuteur, pas un titre d'œuvre — ce que `<cite>` représente selon la spécification ;
 * et dans un mode où tout le corps est du discours rapporté, ne marquer que les
 * intervieweurs comme cités inverserait le sens.
 *
 * Conséquence assumée : le mode entretien ne conserve aucun `blockquote`, la citation en
 * exergue n'y est donc pas disponible. En contrepartie, aucune règle générique de
 * `assets/scss/generic/_blockquote.scss` n'est à neutraliser.
 */
class HtmlInterviewBlocksProcessor implements ProcessorInterface
{
    /**
     * Nombre de couleurs d'intervenant de la palette. Au-delà, les couleurs se répètent.
     *
     * @see assets/scss/components/_interview.scss
     */
    public const SPEAKER_COLORS = 4;

    public function __construct(
        private HtmlCrawlerManagerInterface $crawlers,
        private string $property = 'interviewContent',
    ) {
    }

    public function __invoke(array &$data, Content $content): void
    {
        if (!isset($data[$this->property])) {
            return;
        }

        $crawler = $this->crawlers->get($content, $data, $this->property);

        if (null === $crawler) {
            // Content is not valid HTML.
            return;
        }

        $quotes = $crawler->filter('blockquote');

        if (0 === $quotes->count()) {
            return;
        }

        // Les citations sont collectées avant d'être remplacées : les retirer du document
        // modifierait la DOMNodeList que l'on itère.
        /** @var list<\DOMElement> $elements */
        $elements = iterator_to_array($quotes, false);

        // Les indices de couleur sont attribués par ordre d'apparition dans l'article,
        // et non par hachage du nom : deux intervenants sont ainsi toujours distincts,
        // et un même intervenant garde sa couleur d'un bout à l'autre.
        $speakers = [];

        foreach ($elements as $element) {
            $this->split($element, $speakers);
        }

        $this->crawlers->save($content, $data, $this->property);
    }

    /**
     * Re-découpe une citation en remises en contexte et prises de parole, puis la remplace.
     *
     * @param array<string, int> $speakers Nom => indice de couleur, par référence
     */
    private function split(\DOMElement $quote, array &$speakers): void
    {
        $document = $quote->ownerDocument;
        $parent = $quote->parentNode;

        if (null === $document || null === $parent) {
            return;
        }

        /** @var list<\DOMNode> $children */
        $children = iterator_to_array($quote->childNodes, false);

        /** @var list<array{speaker: string|null, nodes: list<\DOMNode>}> $groups */
        $groups = [];
        $current = ['speaker' => null, 'nodes' => []];

        foreach ($children as $child) {
            if ($this->isBlank($child)) {
                continue;
            }

            if ($child instanceof \DOMElement && null !== ($name = $this->takeLeadingName($child))) {
                // Un nom en tête de paragraphe ouvre une prise de parole.
                if ([] !== $current['nodes']) {
                    $groups[] = $current;
                }

                $current = ['speaker' => $name, 'nodes' => [$child]];

                continue;
            }

            $current['nodes'][] = $child;

            if ($child instanceof \DOMElement && null !== ($name = $this->takeTrailingCite($child))) {
                // Un `<cite>` en fin de paragraphe attribue ce qui précède, et clôt.
                $current['speaker'] = $name;
                $groups[] = $current;
                $current = ['speaker' => null, 'nodes' => []];
            }
        }

        if ([] !== $current['nodes']) {
            $groups[] = $current;
        }

        foreach ($groups as $group) {
            $parent->insertBefore(
                null === $group['speaker']
                    ? $this->createContext($document, $group['nodes'])
                    : $this->createTurn($document, $group['speaker'], $group['nodes'], $speakers),
                $quote,
            );
        }

        $parent->removeChild($quote);
    }

    /**
     * @param list<\DOMNode> $nodes
     */
    private function createContext(\DOMDocument $document, array $nodes): \DOMElement
    {
        $block = $document->createElement('div');
        $block->setAttribute('class', 'interview-context');

        foreach ($nodes as $node) {
            $block->appendChild($node);
        }

        return $block;
    }

    /**
     * @param list<\DOMNode>     $nodes
     * @param array<string, int> $speakers
     */
    private function createTurn(\DOMDocument $document, string $speaker, array $nodes, array &$speakers): \DOMElement
    {
        $speakers[$speaker] ??= \count($speakers) % self::SPEAKER_COLORS + 1;

        $block = $document->createElement('div');
        $block->setAttribute('class', "interview-turn interview-turn--{$speakers[$speaker]}");

        $label = $document->createElement('p');
        $label->setAttribute('class', 'interview-turn__speaker');
        $label->appendChild($document->createTextNode($speaker));
        $block->appendChild($label);

        foreach ($nodes as $node) {
            $block->appendChild($node);
        }

        return $block;
    }

    /**
     * Nom ouvrant le paragraphe — `<strong>Nom :</strong>` ou `<strong>Nom</strong> :` —,
     * retiré du flux au passage. `null` si le paragraphe n'en porte pas.
     */
    private function takeLeadingName(\DOMElement $element): ?string
    {
        $first = $this->firstMeaningfulChild($element);

        if (!$first instanceof \DOMElement || 'strong' !== $first->tagName) {
            return null;
        }

        $text = trim($first->textContent);
        $colonInside = str_ends_with($text, ':');
        $next = $first->nextSibling;
        // Le deux-points est le signal : sans lui, c'est un paragraphe qui commence
        // simplement par de l'emphase, pas une prise de parole.
        $colonAfter = $next instanceof \DOMText && 1 === preg_match('/^\s*:/', $next->textContent);

        if (!$colonInside && !$colonAfter) {
            return null;
        }

        $name = $colonInside ? rtrim(substr($text, 0, -1)) : $text;

        if ('' === $name) {
            return null;
        }

        $first->remove();

        if ($next instanceof \DOMText) {
            $next->textContent = preg_replace('/^\s*:?\s*/', '', $next->textContent) ?? $next->textContent;
        }

        return $name;
    }

    /**
     * Nom clôturant le paragraphe — `<cite>Nom</cite>` —, retiré du flux au passage.
     */
    private function takeTrailingCite(\DOMElement $element): ?string
    {
        $last = $this->lastMeaningfulChild($element);

        if (!$last instanceof \DOMElement || 'cite' !== $last->tagName) {
            return null;
        }

        // Le guide de style montre `<cite>- Jane Doe</cite>` : le tiret d'attribution
        // est décoratif, il n'appartient pas au nom.
        $name = trim(preg_replace('/^[\s\-–—]+/u', '', $last->textContent) ?? $last->textContent);

        if ('' === $name) {
            return null;
        }

        $previous = $last->previousSibling;
        $last->remove();

        if ($previous instanceof \DOMText) {
            $previous->textContent = rtrim($previous->textContent);
        }

        return $name;
    }

    private function firstMeaningfulChild(\DOMElement $element): ?\DOMNode
    {
        for ($child = $element->firstChild; null !== $child; $child = $child->nextSibling) {
            if (!$this->isBlank($child)) {
                return $child;
            }
        }

        return null;
    }

    private function lastMeaningfulChild(\DOMElement $element): ?\DOMNode
    {
        for ($child = $element->lastChild; null !== $child; $child = $child->previousSibling) {
            if (!$this->isBlank($child)) {
                return $child;
            }
        }

        return null;
    }

    /**
     * Nœud de texte sans autre contenu que de l'espace, produit par l'indentation du
     * HTML : il ne compte ni comme premier ni comme dernier enfant.
     */
    private function isBlank(\DOMNode $node): bool
    {
        return $node instanceof \DOMText && '' === trim($node->textContent);
    }
}
