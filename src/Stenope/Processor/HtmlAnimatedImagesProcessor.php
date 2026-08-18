<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use Stenope\Bundle\Behaviour\HtmlCrawlerManagerInterface;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;

/**
 * Enveloppe les images animées du contenu dans un conteneur muni d'une commande
 * de lecture / pause.
 *
 * Un GIF animé démarre seul et boucle indéfiniment : il entre donc dans le champ
 * de WCAG 2.2.2 (RGAA 13.8), qui exige un moyen de mettre en pause, arrêter ou
 * masquer tout mouvement automatique de plus de cinq secondes. Le format n'offre
 * aucun contrôle natif, contrairement à `<video>` — d'où cette commande ajoutée
 * au build.
 *
 * Le conteneur est produit ici plutôt qu'en Twig parce que ces images viennent du
 * markdown des articles : elles n'ont pas de gabarit où insérer un bouton.
 *
 * Seuls les GIF sont concernés. Les PNG et WebP animés existent mais le dépôt
 * n'en contient aucun, et les détecter demanderait de lire l'en-tête de chaque
 * fichier — on s'en tiendra à l'extension tant que ce n'est pas nécessaire.
 *
 * La mise en pause elle-même est faite côté client (`animated_image_controller`) :
 * elle consiste à figer l'image courante sur un canvas. Rien ne peut la produire
 * au build, puisqu'il s'agit de l'état de l'animation au moment du clic.
 */
class HtmlAnimatedImagesProcessor implements ProcessorInterface
{
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

        $images = $crawler->filter('img');

        if (0 === $images->count()) {
            return;
        }

        // Les images sont collectées avant d'être déplacées : insérer un conteneur
        // dans le document modifie la DOMNodeList que l'on itère.
        /** @var list<\DOMElement> $elements */
        $elements = iterator_to_array($images, false);

        $wrapped = false;

        foreach ($elements as $element) {
            if ($this->isAnimated($element)) {
                $this->wrap($element);
                $wrapped = true;
            }
        }

        if ($wrapped) {
            $this->crawlers->save($content, $data, $this->property);
        }
    }

    /**
     * L'extension est lue sur le chemin seul : une URL de contenu peut porter une
     * chaîne de requête (redimensionnement Glide) ou une ancre.
     */
    private function isAnimated(\DOMElement $image): bool
    {
        $src = $image->getAttribute('src');

        if ('' === $src) {
            return false;
        }

        $path = parse_url($src, \PHP_URL_PATH);

        if (!\is_string($path)) {
            return false;
        }

        return 'gif' === strtolower(pathinfo($path, \PATHINFO_EXTENSION));
    }

    private function wrap(\DOMElement $image): void
    {
        $document = $image->ownerDocument;
        $parent = $image->parentNode;

        if (null === $document || null === $parent) {
            return;
        }

        $wrapper = $document->createElement('div');
        $wrapper->setAttribute('class', 'animated-image');
        $wrapper->setAttribute('data-controller', 'animated-image');

        $parent->replaceChild($wrapper, $image);
        $wrapper->appendChild($image);

        $image->setAttribute('data-animated-image-target', 'image');

        $wrapper->appendChild($this->createToggle($document));
    }

    /**
     * Le nom accessible du bouton change avec l'état plutôt que d'être fixe et
     * doublé d'un `aria-pressed` : les deux mécanismes ensemble se recouvrent, et
     * « Lancer l'animation » dit à lui seul ce que fera l'activation.
     *
     * Les deux pictogrammes sont posés ici et permutés par la feuille de style
     * selon l'état : le bouton reste utilisable si le JS échoue à se charger —
     * il ne fera rien, mais il n'affichera pas non plus un état mensonger.
     */
    private function createToggle(\DOMDocument $document): \DOMElement
    {
        $button = $document->createElement('button');
        $button->setAttribute('type', 'button');
        $button->setAttribute('class', 'animated-image__toggle');
        $button->setAttribute('data-animated-image-target', 'toggle');
        $button->setAttribute('data-action', 'animated-image#toggle');

        $icons = $document->createElement('span');
        $icons->setAttribute('class', 'animated-image__icons');
        $icons->setAttribute('aria-hidden', 'true');
        $icons->appendChild($this->createIcon($document, 'pause'));
        $icons->appendChild($this->createIcon($document, 'play'));
        $button->appendChild($icons);

        $label = $document->createElement('span', 'Mettre l’animation en pause');
        $label->setAttribute('class', 'screen-reader');
        $label->setAttribute('data-animated-image-target', 'label');
        $button->appendChild($label);

        return $button;
    }

    private function createIcon(\DOMDocument $document, string $name): \DOMElement
    {
        $svg = $document->createElement('svg');
        $svg->setAttribute('class', "animated-image__icon animated-image__icon--$name");
        $svg->setAttribute('viewBox', '0 0 24 24');
        $svg->setAttribute('width', '18');
        $svg->setAttribute('height', '18');
        $svg->setAttribute('focusable', 'false');
        $svg->setAttribute('aria-hidden', 'true');

        $path = $document->createElement('path');
        $path->setAttribute('fill', 'currentColor');
        $path->setAttribute('d', 'pause' === $name
            ? 'M8 5h3v14H8zm5 0h3v14h-3z'
            : 'M8 5l11 7-11 7z');
        $svg->appendChild($path);

        return $svg;
    }
}
