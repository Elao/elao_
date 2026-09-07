<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use App\Model\Article;
use Stenope\Bundle\Behaviour\HtmlCrawlerManagerInterface;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Découpe un article au format entretien en une zone commune et un corps par mode
 * de lecture.
 *
 * La rédaction sépare les modes par un commentaire HTML — `<!-- mode: entretien -->` —,
 * que Parsedown laisse traverser intact : rien à parser en amont, et un aperçu markdown
 * ne montre aucun artefact. Ce qui précède le premier commentaire est la zone commune,
 * rendue quel que soit le mode affiché.
 *
 * Le processor s'exécute tard (priorité -95) : images, liens, coloration, ancres et
 * notes ont déjà été appliqués au contenu entier, il n'y a donc rien à rejouer par mode.
 *
 * Deux précautions :
 *
 * - Le corps de chaque mode est **déplacé** hors du DOM du crawler `content`, jamais
 *   recopié. {@see \Stenope\Bundle\Service\SharedHtmlCrawlerManager::saveAll()} réécrit
 *   `$data['content']` depuis le crawler après tous les processors : réaffecter la chaîne
 *   serait écrasé, élaguer l'arbre est la seule façon de restreindre la zone commune.
 * - Les `id` de titres sont préfixés par le slug du mode. Sans cela, deux sections
 *   homonymes — l'un des deux modes reprend volontiers les thèmes de l'autre — porteraient
 *   le même `id` dans une même page, et les deux sommaires viseraient la même ancre.
 *   Les liens internes qui les visaient sont réécrits en conséquence, y compris l'ancre
 *   que {@see HtmlAnchorProcessor} a posée dans le titre lui-même.
 *
 * Les `id` de notes (`footnote-ref-N`) ne sont pas préfixés : le gabarit les référence
 * depuis la liste de notes, qui est commune aux deux modes.
 */
class ArticleReadingModesProcessor implements ProcessorInterface
{
    /**
     * Slug de mode => propriétés de contenu et de sommaire qu'il alimente.
     */
    private const MODES = [
        Article::READING_MODE_NARRATIVE => ['narrativeContent', 'narrativeTableOfContent'],
        Article::READING_MODE_INTERVIEW => ['interviewContent', 'interviewTableOfContent'],
    ];

    private const SEPARATOR_PATTERN = '/^\s*mode\s*:\s*([a-z-]+)\s*$/i';

    public function __construct(
        private HtmlCrawlerManagerInterface $crawlers,
        private string $property = 'content',
        private string $tableOfContentProperty = 'tableOfContent',
    ) {
    }

    public function __invoke(array &$data, Content $content): void
    {
        if (Article::TYPE_INTERVIEW !== ($data['type'] ?? Article::TYPE_POST)) {
            return;
        }

        if (!is_a($content->getType(), Article::class, true)) {
            return;
        }

        if (!isset($data[$this->property])) {
            return;
        }

        $crawler = $this->crawlers->get($content, $data, $this->property);

        if (null === $crawler) {
            // Content is not valid HTML.
            return;
        }

        $body = $crawler->filterXPath('//body')->getNode(0);

        if (null === $body) {
            return;
        }

        $this->restoreHoistedSeparators($body);

        $segments = $this->split($body, $content);

        if ([] === $segments) {
            return;
        }

        $document = $body->ownerDocument;

        if (null === $document) {
            return;
        }

        // Chaque mode est isolé dans un conteneur temporaire : le déplacement l'extrait
        // du corps commun, et le conteneur borne les recherches de titres et de liens.
        $wrappers = [];

        foreach ($segments as $slug => $nodes) {
            $wrapper = $document->createElement('div');
            $body->appendChild($wrapper);

            foreach ($nodes as $node) {
                $wrapper->appendChild($node);
            }

            $wrappers[$slug] = $wrapper;
        }

        $xpath = new \DOMXPath($document);
        $maps = [];

        foreach ($wrappers as $slug => $wrapper) {
            $maps[$slug] = $this->prefixHeadingIds($wrapper, $slug);
        }

        $shared = $this->sharedMap($maps);

        foreach ($wrappers as $slug => $wrapper) {
            // Un lien d'un mode vers une section homonyme vise la sienne, pas celle de l'autre.
            $this->rewriteLinks($xpath, $wrapper, $maps[$slug] + $shared);
        }

        $depth = $data[$this->tableOfContentProperty] ?? null;

        foreach ($wrappers as $slug => $wrapper) {
            [$contentProperty, $tableOfContentProperty] = self::MODES[$slug];
            $data[$contentProperty] = (new Crawler($wrapper))->html();
            $body->removeChild($wrapper);

            if (\is_int($depth) || true === $depth) {
                $data[$tableOfContentProperty] = $depth;
            }
        }

        // Les conteneurs de mode sont détachés : le corps ne porte plus que la zone
        // commune, une seule évaluation suffit donc pour ses liens.
        $this->rewriteLinks($xpath, $body, $shared);

        // Désactive le sommaire principal : il ne porterait plus que les titres de la
        // zone commune. `false` n'est ni un entier ni `true`, `TableOfContentProcessor`
        // retire alors la clé et la dénormalisation retombe sur la valeur par défaut.
        $data[$this->tableOfContentProperty] = false;
    }

    /**
     * Ramène dans le corps les séparateurs que l'analyseur a hissés au niveau du document.
     *
     * Quand le contenu s'ouvre sur un commentaire — un article dont le premier mode
     * commence dès la première ligne, sans zone commune —, libxml le place avant
     * `<html>` plutôt que dans `<body>`. {@see self::split()} ne le verrait pas, et
     * tout le premier mode basculerait silencieusement dans la zone commune.
     *
     * Seuls les séparateurs sont déplacés : un autre commentaire de tête est laissé
     * où l'analyseur l'a mis, son sort ne change pas.
     */
    private function restoreHoistedSeparators(\DOMNode $body): void
    {
        $document = $body->ownerDocument;

        if (null === $document) {
            return;
        }

        /** @var list<\DOMNode> $children */
        $children = iterator_to_array($document->childNodes, false);
        $hoisted = [];

        foreach ($children as $node) {
            if ($node === $document->documentElement) {
                break;
            }

            if (null !== $this->readSeparator($node)) {
                $hoisted[] = $node;
            }
        }

        foreach (array_reverse($hoisted) as $separator) {
            $body->insertBefore($separator, $body->firstChild);
        }
    }

    /**
     * Regroupe les enfants du corps par mode, en coupant sur les commentaires
     * séparateurs, qui sont retirés au passage. Ce qui précède le premier séparateur
     * n'est pas collecté : la zone commune est ce qui restera du corps une fois les
     * modes déplacés.
     *
     * @return array<string, list<\DOMNode>>
     */
    private function split(\DOMNode $body, Content $content): array
    {
        $segments = [];
        $current = null;

        /** @var list<\DOMNode> $children */
        $children = iterator_to_array($body->childNodes, false);

        foreach ($children as $node) {
            $slug = $this->readSeparator($node);

            if (null === $slug) {
                if (null !== $current) {
                    $segments[$current][] = $node;
                }

                continue;
            }

            if (!isset(self::MODES[$slug])) {
                throw new \LogicException(sprintf(
                    'Mode de lecture inconnu "%s" dans "%s". Modes attendus : %s.',
                    $slug,
                    $content->getSlug(),
                    implode(', ', array_keys(self::MODES)),
                ));
            }

            $current = $slug;
            $segments[$slug] ??= [];
            $body->removeChild($node);
        }

        return $segments;
    }

    /**
     * Slug du mode qu'ouvre ce nœud, s'il s'agit d'un commentaire séparateur.
     */
    private function readSeparator(\DOMNode $node): ?string
    {
        if (!$node instanceof \DOMComment) {
            return null;
        }

        if (1 !== preg_match(self::SEPARATOR_PATTERN, $node->textContent, $matches)) {
            return null;
        }

        return strtolower($matches[1]);
    }

    /**
     * @return array<string, string> Identifiant d'origine => identifiant préfixé
     */
    private function prefixHeadingIds(\DOMElement $wrapper, string $slug): array
    {
        $map = [];

        /** @var \DOMElement $heading */
        foreach ((new Crawler($wrapper))->filter('h1[id], h2[id], h3[id], h4[id], h5[id], h6[id]') as $heading) {
            $id = $heading->getAttribute('id');
            $map[$id] = "$slug-$id";
            $heading->setAttribute('id', $map[$id]);
        }

        return $map;
    }

    /**
     * Table des identifiants utilisable depuis n'importe où dans l'article.
     *
     * Un identifiant que les deux modes portent est ambigu hors de ceux-ci : il est
     * alors résolu vers le premier mode de l'article, celui affiché à l'arrivée.
     * Le laisser tel quel ne serait pas neutre — le préfixage a renommé les deux
     * titres, et l'identifiant d'origine ne désigne plus rien dans la page.
     *
     * @param array<string, array<string, string>> $maps
     *
     * @return array<string, string>
     */
    private function sharedMap(array $maps): array
    {
        $shared = [];

        foreach ($maps as $map) {
            foreach ($map as $id => $prefixed) {
                $shared[$id] ??= $prefixed;
            }
        }

        return $shared;
    }

    /**
     * @param array<string, string> $map
     */
    private function rewriteLinks(\DOMXPath $xpath, \DOMNode $scope, array $map): void
    {
        if ([] === $map) {
            return;
        }

        $links = $xpath->query('descendant-or-self::a[starts-with(@href, "#")]', $scope);

        if (false === $links) {
            return;
        }

        foreach ($links as $link) {
            if (!$link instanceof \DOMElement) {
                continue;
            }

            $target = substr($link->getAttribute('href'), 1);

            if (isset($map[$target])) {
                $link->setAttribute('href', '#' . $map[$target]);
            }
        }
    }
}
