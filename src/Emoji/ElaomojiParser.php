<?php

declare(strict_types=1);

namespace App\Emoji;

use App\Model\Misc;
use Stenope\Bundle\ContentManager;
use Symfony\Component\Asset\Packages;

/**
 * Replace elaomoji code in a string by actual elaomoji
 */
class ElaomojiParser
{
    private ContentManager $contentManager;
    private Packages $packages;

    public function __construct(ContentManager $contentManager, Packages $packages)
    {
        $this->contentManager = $contentManager;
        $this->packages = $packages;
    }

    public function parse(string $content): string
    {
        /** @var Misc $config */
        $config = $this->contentManager->getContent(Misc::class, 'elaomojis');

        // Array of emojis code => path
        $emojis = array_column(array_merge(...array_column(array_merge(...array_column($config->categories, 'sections')), 'emojis')), 'path', 'code');

        $content = preg_replace_callback(
            '/(:[a-z0-9\+\-]+:)/',
            fn (array $matches) => isset($emojis[$matches[1]]) ? $this->img($emojis[$matches[1]], $matches[1]) : $matches[0],
            $content,
        );

        if (null === $content) {
            throw new \Exception('Fail to replace emoji code.');
        }

        return $content;
    }

    private function img(string $path, string $code): string
    {
        return sprintf(
            '<img class="emoji" src="%s" alt="%s">',
            $this->packages->getUrl('build/images/elaomojis/' . $path),
            htmlspecialchars($this->altFromCode($code), \ENT_QUOTES),
        );
    }

    /**
     * Construit un texte alternatif lisible à partir du code de l'émoji.
     *
     * `:amelie-happy:` devenait `alt="amelie-happy"` : un slug, lu tel quel par les
     * lecteurs d'écran. Faute de libellé rédigé dans `content/misc/elaomojis.yaml`
     * (les 86 entrées n'ont que `code` et `path`), on humanise le code et on le
     * préfixe pour signaler la nature de l'image. Reste une approximation : de vrais
     * libellés éditoriaux feraient mieux.
     */
    private function altFromCode(string $code): string
    {
        return 'Elaomoji ' . str_replace('-', ' ', trim($code, ':'));
    }
}
