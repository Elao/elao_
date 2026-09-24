<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use App\Model\Article;
use Stenope\Bundle\Behaviour\HtmlCrawlerManagerInterface;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;

/**
 * Remplace les appels de notes `[^…]` du contenu par un exposant lié à la note
 * correspondante, et normalise `footnotes` pour le template.
 *
 * Quatre formes d'appel, toutes résolues vers le même numéro :
 *
 *     [^1]                  1re note de l'article
 *     [^chronologie]        note portant cette clé, quel que soit son groupe
 *     [^sources:1]          1re note du groupe `sources`
 *     [^sources:barometre]  note `barometre` du groupe `sources`
 *
 * La numérotation est continue d'un groupe à l'autre : les groupes titrent des
 * sections d'affichage, ils ne renumérotent pas. Deux exposants « 1 » dans le
 * même article seraient indiscernables à la lecture.
 *
 * L'intitulé du lien ne peut pas se réduire au numéro : « 1 » seul n'est pas un
 * nom accessible explicite (RGAA 6.1). On préfixe donc d'un texte réservé aux
 * technologies d'assistance plutôt que de compter sur un attribut `title`, qui
 * n'entre dans le calcul du nom accessible qu'à défaut de contenu.
 *
 * @phpstan-type Note array{number: int, key: string|null, text: string, url: string|null, source: string|null, anchored: bool}
 * @phpstan-type Group array{title: string, key: string|null, notes: list<Note>}
 *
 * @see \App\Model\Article::$footnotes
 */
class HtmlFootnotesProcessor implements ProcessorInterface
{
    public const DEFAULT_TITLE = 'Notes et références';

    /**
     * Un appel de note : `[^1]`, `[^cle]`, `[^groupe:1]` ou `[^groupe:cle]`.
     */
    private const MARKER_PATTERN = '/\[\^([a-zA-Z0-9_:-]+)]/';

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

        if (!\is_array($data['footnotes'] ?? null) || [] === $data['footnotes']) {
            return;
        }

        $groups = $this->normalize($data['footnotes']);

        // Le template reçoit la forme canonique : titres résolus, notes numérotées.
        $data['footnotes'] = $groups;

        if ([] === $groups || !isset($data[$this->property])) {
            return;
        }

        $crawler = $this->crawlers->get($content, $data, $this->property);

        if (null === $crawler) {
            // Content is not valid HTML.
            return;
        }

        // Une même note peut être appelée plusieurs fois : seul le premier appel
        // porte l'ancre de retour, sinon l'id serait dupliqué.
        $anchored = [];

        foreach ($this->collectMarkerNodes($crawler->getNode(0)) as $node) {
            $this->replaceMarkers($node, $groups, $anchored);
        }

        // Une note que le corps n'appelle jamais n'a pas d'ancre de retour : le gabarit
        // ne doit pas en proposer une. C'est le cas d'une bibliographie, rassemblée en
        // fin d'article sans être appelée depuis le texte.
        $data['footnotes'] = $this->markAnchored($groups, $anchored);

        $this->crawlers->save($content, $data, $this->property);
    }

    /**
     * Reporte sur chaque note le fait qu'elle a reçu une ancre de retour dans le corps.
     *
     * @param list<Group>      $groups
     * @param array<int, true> $anchored Numéros des notes réellement appelées
     *
     * @return list<Group>
     */
    private function markAnchored(array $groups, array $anchored): array
    {
        foreach ($groups as $groupIndex => $group) {
            foreach ($group['notes'] as $noteIndex => $note) {
                $groups[$groupIndex]['notes'][$noteIndex]['anchored'] = isset($anchored[$note['number']]);
            }
        }

        return $groups;
    }

    /**
     * Accepte deux écritures en front-matter : une liste de notes (groupe unique,
     * titre par défaut) ou une liste de groupes titrés.
     *
     * @param array<mixed> $footnotes
     *
     * @return list<Group>
     */
    private function normalize(array $footnotes): array
    {
        $first = $footnotes[array_key_first($footnotes)] ?? null;
        $grouped = \is_array($first) && isset($first['notes']);
        $rawGroups = $grouped ? $footnotes : [['notes' => $footnotes]];

        $groups = [];
        $number = 0;

        foreach ($rawGroups as $rawGroup) {
            if (!\is_array($rawGroup) || !\is_array($rawGroup['notes'] ?? null)) {
                continue;
            }

            $notes = [];

            foreach ($rawGroup['notes'] as $rawNote) {
                if (!\is_array($rawNote) || !isset($rawNote['text'])) {
                    continue;
                }

                $notes[] = [
                    'number' => ++$number,
                    'key' => isset($rawNote['key']) ? (string) $rawNote['key'] : null,
                    'text' => (string) $rawNote['text'],
                    'url' => isset($rawNote['url']) ? (string) $rawNote['url'] : null,
                    'source' => isset($rawNote['source']) ? (string) $rawNote['source'] : null,
                    // Passé à `true` par {@see self::markAnchored()} si le corps appelle la note.
                    'anchored' => false,
                ];
            }

            if ([] === $notes) {
                continue;
            }

            $groups[] = [
                'title' => isset($rawGroup['title']) ? (string) $rawGroup['title'] : self::DEFAULT_TITLE,
                'key' => isset($rawGroup['key']) ? (string) $rawGroup['key'] : null,
                'notes' => $notes,
            ];
        }

        return $groups;
    }

    /**
     * Résout un appel vers le numéro de la note visée, ou null s'il ne correspond
     * à aucune note : un appel resté littéral vaut mieux qu'une ancre morte.
     *
     * @param list<Group> $groups
     */
    private function resolve(string $reference, array $groups): ?int
    {
        $parts = explode(':', $reference, 2);
        $target = array_pop($parts);
        $groupKey = $parts[0] ?? null;

        // Sans groupe, un numéro est absolu : il désigne directement une note.
        if (null === $groupKey && ctype_digit($target)) {
            return $this->hasNumber((int) $target, $groups) ? (int) $target : null;
        }

        foreach ($groups as $group) {
            if (null !== $groupKey && $group['key'] !== $groupKey) {
                continue;
            }

            if (ctype_digit($target)) {
                return $group['notes'][((int) $target) - 1]['number'] ?? null;
            }

            foreach ($group['notes'] as $note) {
                if (null !== $note['key'] && $note['key'] === $target) {
                    return $note['number'];
                }
            }
        }

        return null;
    }

    /**
     * @param list<Group> $groups
     */
    private function hasNumber(int $number, array $groups): bool
    {
        foreach ($groups as $group) {
            foreach ($group['notes'] as $note) {
                if ($note['number'] === $number) {
                    return true;
                }
            }
        }

        return false;
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
     *
     * @param list<Group>      $groups
     * @param array<int, true> $anchored Notes ayant déjà reçu leur ancre de retour
     */
    private function replaceMarkers(\DOMText $node, array $groups, array &$anchored): void
    {
        $document = $node->ownerDocument;
        $parent = $node->parentNode;

        if (null === $document || null === $parent) {
            return;
        }

        // Alterne texte / référence capturée : les index impairs sont les appels.
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

            $number = $this->resolve($part, $groups);

            // Un appel sans note correspondante produirait une ancre morte : on le laisse tel quel.
            if (null === $number) {
                $fragment->appendChild($document->createTextNode("[^$part]"));

                continue;
            }

            $fragment->appendChild($this->createReference($document, $number, !isset($anchored[$number])));
            $anchored[$number] = true;
        }

        $parent->replaceChild($fragment, $node);
    }

    private function createReference(\DOMDocument $document, int $number, bool $anchor): \DOMElement
    {
        $sup = $document->createElement('sup');
        $sup->setAttribute('class', 'footnote-ref');

        $link = $document->createElement('a');
        $link->setAttribute('href', "#footnote-$number");

        if ($anchor) {
            $link->setAttribute('id', "footnote-ref-$number");
        }

        $label = $document->createElement('span');
        $label->setAttribute('class', 'screen-reader');
        $label->appendChild($document->createTextNode('Voir la note '));

        $link->appendChild($label);
        $link->appendChild($this->createBracket($document, '['));
        $link->appendChild($document->createTextNode((string) $number));
        $link->appendChild($this->createBracket($document, ']'));
        $sup->appendChild($link);

        return $sup;
    }

    /**
     * Les crochets sont une convention typographique : ils restent dans le HTML
     * pour survivre au copier-coller, mais hors du nom accessible, qui doit se
     * lire « Voir la note 1 ».
     */
    private function createBracket(\DOMDocument $document, string $bracket): \DOMElement
    {
        $span = $document->createElement('span');
        $span->setAttribute('aria-hidden', 'true');
        $span->appendChild($document->createTextNode($bracket));

        return $span;
    }
}
