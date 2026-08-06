<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use App\Model\Article;
use Stenope\Bundle\Behaviour\HtmlCrawlerManagerInterface;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;

/**
 * Remplace les appels de notes `[^n]` du contenu par un exposant lié à la note
 * correspondante du bloc "footnotes", rendu en bas d'article par le template.
 *
 * L'intitulé du lien ne peut pas se réduire au numéro : « 1 » seul n'est pas un
 * nom accessible explicite (RGAA 6.1). On préfixe donc d'un texte réservé aux
 * technologies d'assistance plutôt que de compter sur un attribut `title`, qui
 * n'entre dans le calcul du nom accessible qu'à défaut de contenu.
 *
 * @see \App\Model\Article::$footnotes
 */
class HtmlFootnotesProcessor implements ProcessorInterface
{
    /**
     * Un appel de note, tel qu'écrit dans le Markdown : `[^1]`.
     */
    private const MARKER_PATTERN = '/\[\^(\d+)]/';

    public function __construct(
        private HtmlCrawlerManagerInterface $crawlers,
        private string $property = 'content',
    ) {
    }

    public function __invoke(array &$data, Content $content): void
    {
        if (!is_a($content->getType(), Article::class, true)) {
            return;
        }

        if (!isset($data[$this->property]) || !\is_array($data['footnotes'] ?? null)) {
            return;
        }

        $crawler = $this->crawlers->get($content, $data, $this->property);

        if (null === $crawler) {
            // Content is not valid HTML.
            return;
        }

        $total = \count($data['footnotes']);

        // Une même note peut être appelée plusieurs fois : seul le premier appel
        // porte l'ancre de retour, sinon l'id serait dupliqué.
        $anchored = [];

        foreach ($this->collectMarkerNodes($crawler->getNode(0)) as $node) {
            $this->replaceMarkers($node, $total, $anchored);
        }

        $this->crawlers->save($content, $data, $this->property);
    }

    /**
     * Collecte les nœuds texte contenant un appel de note, hors code où `[^1]`
     * dénote une classe de caractères et non un appel.
     *
     * La collecte est volontairement faite avant toute modification : scinder un
     * nœud texte pendant l'itération d'une DOMNodeList vivante ferait sauter des
     * occurrences.
     *
     * @return list<\DOMText>
     */
    private function collectMarkerNodes(?\DOMNode $root): array
    {
        $document = $root instanceof \DOMDocument ? $root : $root?->ownerDocument;

        if (null === $root || null === $document) {
            return [];
        }

        $found = (new \DOMXPath($document))->query('.//text()[not(ancestor::code) and not(ancestor::pre)]', $root);

        if (false === $found) {
            return [];
        }

        $nodes = [];

        foreach ($found as $node) {
            if ($node instanceof \DOMText && 1 === preg_match(self::MARKER_PATTERN, $node->data)) {
                $nodes[] = $node;
            }
        }

        return $nodes;
    }

    /**
     * Remplace le nœud texte par la même chaîne, appels de notes convertis en exposants.
     */
    /**
     * @param array<int, true> $anchored Notes ayant déjà reçu leur ancre de retour
     */
    private function replaceMarkers(\DOMText $node, int $total, array &$anchored): void
    {
        $document = $node->ownerDocument;
        $parent = $node->parentNode;

        if (null === $document || null === $parent) {
            return;
        }

        // Alterne texte / numéro capturé : les index impairs sont les appels.
        $parts = preg_split(self::MARKER_PATTERN, $node->data, -1, PREG_SPLIT_DELIM_CAPTURE);

        if (false === $parts) {
            return;
        }

        $fragment = $document->createDocumentFragment();

        foreach ($parts as $i => $part) {
            if (0 === $i % 2) {
                if ('' !== $part) {
                    $fragment->appendChild($document->createTextNode($part));
                }

                continue;
            }

            $index = (int) $part;

            // Un appel sans note correspondante produirait une ancre morte : on le laisse tel quel.
            if ($index < 1 || $index > $total) {
                $fragment->appendChild($document->createTextNode("[^$part]"));

                continue;
            }

            $fragment->appendChild($this->createReference($document, $index, !isset($anchored[$index])));
            $anchored[$index] = true;
        }

        $parent->replaceChild($fragment, $node);
    }

    private function createReference(\DOMDocument $document, int $index, bool $anchor): \DOMElement
    {
        $sup = $document->createElement('sup');
        $sup->setAttribute('class', 'footnote-ref');

        $link = $document->createElement('a');
        $link->setAttribute('href', "#footnote-$index");

        if ($anchor) {
            $link->setAttribute('id', "footnote-ref-$index");
        }

        $label = $document->createElement('span');
        $label->setAttribute('class', 'screen-reader');
        $label->appendChild($document->createTextNode('Voir la note '));

        $link->appendChild($label);
        $link->appendChild($document->createTextNode("[$index]"));
        $sup->appendChild($link);

        return $sup;
    }
}
