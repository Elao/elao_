<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use Stenope\Bundle\Behaviour\HtmlCrawlerManagerInterface;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;

/**
 * Enveloppe les images animées du contenu dans un conteneur confié à
 * `animated_image_controller`, qui y pose une commande de lecture / pause.
 *
 * Un GIF animé démarre seul et boucle indéfiniment : il entre donc dans le champ
 * de WCAG 2.2.2 (RGAA 13.8), qui exige un moyen de mettre en pause, arrêter ou
 * masquer tout mouvement automatique de plus de cinq secondes. Le format n'offre
 * aucun contrôle natif, contrairement à `<video>` — d'où cette commande.
 *
 * Le conteneur est produit ici plutôt qu'en Twig parce que ces images viennent du
 * markdown des articles : elles n'ont pas de gabarit où l'insérer. Et il est
 * produit au build plutôt que côté client parce qu'il porte la mise en page :
 * `.animated-image` reprend la marge que `generic/_img.scss` posait sur l'image,
 * l'ajouter après coup décalerait le contenu.
 *
 * La commande, elle, est posée par le contrôleur. Son existence et son libellé
 * dépendent de ce que le navigateur sait faire : un GIF d'une seule image n'a rien
 * à contrôler, et sans `ImageDecoder` la commande arrête l'animation au lieu de la
 * suspendre. Rien de tout cela n'est connu au build — un bouton rendu ici serait
 * affirmé avant qu'on sache s'il aura un effet, et inerte si le script échoue à se
 * charger.
 *
 * Seuls les GIF sont concernés, elaomojis exclus. Les PNG et WebP animés existent
 * mais le dépôt n'en contient aucun, et les détecter demanderait de lire l'en-tête
 * de chaque fichier — on s'en tiendra à l'extension tant que ce n'est pas nécessaire.
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

        // Les elaomojis sont des GIF, mais des GIF de la taille d'un caractère, posés
        // au fil du texte par `ElaomojisProcessor` (`.emoji` vaut `height: 1em` et
        // `display: inline-block`). Les enrober les arracherait de leur phrase — le
        // conteneur est un bloc centré avec ses propres marges — et la commande y
        // serait plus grande que l'image qu'elle contrôle. Le critère vise le
        // mouvement qui s'impose à la lecture, pas un emoji de 25 px au fil d'un texte.
        $images = $crawler->filter('img:not(.emoji)');

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

        if (null === $document) {
            return;
        }

        // Quand l'image est le seul contenu de son paragraphe — ce que produit le
        // markdown `![…](…)` —, le conteneur prend la place du paragraphe au lieu de
        // s'y nicher : un `<div>` dans un `<p>` est invalide, et l'analyseur du
        // navigateur le remonte hors du paragraphe en synthétisant des paragraphes
        // vides. L'arbre rendu ne correspondrait alors plus à celui pour lequel la
        // feuille de style est écrite.
        $target = $this->soleParagraphOf($image) ?? $image;
        $parent = $target->parentNode;

        if (null === $parent) {
            return;
        }

        $wrapper = $document->createElement('div');
        $wrapper->setAttribute('class', 'animated-image');
        $wrapper->setAttribute('data-controller', 'animated-image');

        $parent->replaceChild($wrapper, $target);
        $wrapper->appendChild($image);

        $image->setAttribute('data-animated-image-target', 'image');
    }

    /**
     * Le paragraphe dont l'image est le seul contenu, s'il y en a un.
     */
    private function soleParagraphOf(\DOMElement $image): ?\DOMElement
    {
        $parent = $image->parentNode;

        if (!$parent instanceof \DOMElement || 'p' !== $parent->nodeName) {
            return null;
        }

        foreach ($parent->childNodes as $node) {
            if ($node === $image) {
                continue;
            }

            // Le markdown laisse des retours à la ligne autour de l'image : seul un
            // contenu visible interdit de remplacer le paragraphe.
            if ($node instanceof \DOMText && '' === trim($node->wholeText)) {
                continue;
            }

            return null;
        }

        return $parent;
    }
}
