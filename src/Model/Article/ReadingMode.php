<?php

declare(strict_types=1);

namespace App\Model\Article;

use Stenope\Bundle\TableOfContent\TableOfContent;

/**
 * Un mode de lecture d'un article au format entretien : le corps de texte, son
 * sommaire, et de quoi étiqueter le contrôle qui l'active.
 *
 * Assemblé à la volée par {@see \App\Model\Article::getReadingModes()} à partir
 * des propriétés remplies par les processors et de l'en-tête de l'article. Cet
 * objet n'existe que pour garder le gabarit lisible : il n'est jamais dénormalisé
 * depuis le contenu.
 */
final class ReadingMode
{
    public function __construct(
        /**
         * Identifie le mode dans le fragment d'URL et préfixe les `id` de ses titres.
         */
        public readonly string $slug,
        public readonly string $label,
        /**
         * Durée de lecture en minutes, telle que renseignée par la rédaction.
         */
        public readonly ?int $readingTime,
        public readonly string $content,
        public readonly ?TableOfContent $tableOfContent,
        /**
         * Mode affiché à l'arrivée, en l'absence d'indication dans l'adresse.
         */
        public readonly bool $default,
    ) {
    }
}
