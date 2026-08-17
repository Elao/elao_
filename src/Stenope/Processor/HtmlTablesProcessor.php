<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use Stenope\Bundle\Behaviour\HtmlCrawlerManagerInterface;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;

/**
 * Enveloppe les tableaux du contenu dans un conteneur défilable horizontalement,
 * pour les écrans trop étroits pour les afficher en entier.
 *
 * Le conteneur ne peut pas être produit par la feuille de style : une zone qui
 * défile doit être atteignable au clavier (RGAA 12.7, WCAG 2.1.1), donc porter
 * un `tabindex`, et son arrêt de tabulation doit s'annoncer — un arrêt muet
 * laisse la personne qui l'atteint sans savoir ce qu'elle vient d'atteindre.
 * D'où le `role` et le nom accessible, emprunté au titre qui précède le tableau.
 *
 * Le rôle retenu est `group` et non `region` : le nom est annoncé à la prise de
 * focus dans les deux cas, mais `region` ajouterait un point de repère par
 * tableau à la liste des repères de la page, que l'on parcourt justement pour
 * s'orienter entre les grandes zones du document.
 *
 * Limite connue : le conteneur est un arrêt de tabulation même quand le tableau
 * tient à l'écran et n'a donc rien à faire défiler. Ne le poser qu'au besoin
 * demanderait de mesurer le tableau après rendu, donc du JS ; c'est un arbitrage
 * assumé en faveur d'une solution sans script.
 */
class HtmlTablesProcessor implements ProcessorInterface
{
    /**
     * Nom de repli, pour un tableau qu'aucun titre ne précède.
     */
    public const DEFAULT_LABEL = 'Tableau';

    public function __construct(
        private HtmlCrawlerManagerInterface $crawlers,
        private string $property = 'content',
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

        $tables = $crawler->filter('table');

        if (0 === $tables->count()) {
            return;
        }

        // Les tableaux sont collectés avant d'être déplacés : insérer un
        // conteneur dans le document modifie la DOMNodeList que l'on itère.
        /** @var list<\DOMElement> $elements */
        $elements = iterator_to_array($tables, false);

        foreach ($elements as $element) {
            $this->wrap($element);
        }

        $this->crawlers->save($content, $data, $this->property);
    }

    private function wrap(\DOMElement $table): void
    {
        $document = $table->ownerDocument;
        $parent = $table->parentNode;

        if (null === $document || null === $parent) {
            return;
        }

        $wrapper = $document->createElement('div');
        $wrapper->setAttribute('class', 'table-scroll');
        $wrapper->setAttribute('tabindex', '0');
        $wrapper->setAttribute('role', 'group');

        // Le nom est recopié plutôt que référencé par `aria-labelledby` : il est
        // extrait du titre au moment du build, donc il ne peut pas en diverger,
        // et il ne dépend pas de l'unicité des ids de titres — deux sections
        // homonymes dans un même article produisent aujourd'hui le même id.
        $label = trim($this->findPrecedingHeading($table)?->textContent ?? '');

        $wrapper->setAttribute('aria-label', '' !== $label ? $label : self::DEFAULT_LABEL);

        $parent->replaceChild($wrapper, $table);
        $wrapper->appendChild($table);
    }

    /**
     * Dernier titre rencontré avant le tableau dans l'ordre du document, quel
     * que soit son niveau : c'est celui sous lequel le tableau se lit.
     */
    private function findPrecedingHeading(\DOMElement $table): ?\DOMElement
    {
        $document = $table->ownerDocument;

        if (null === $document) {
            return null;
        }

        $headings = (new \DOMXPath($document))->query(
            'preceding::*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6][1]',
            $table,
        );

        if (false === $headings) {
            return null;
        }

        $heading = $headings->item(0);

        return $heading instanceof \DOMElement ? $heading : null;
    }
}
