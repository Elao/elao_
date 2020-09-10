<?php

/*
 * This file is part of the "Tom32i/Content" bundle.
 *
 * @author Thomas Jarrand <thomas.jarrand@gmail.com>
 */

namespace App\Content\Processor;

use App\Model\Article;
use Content\Behaviour\ProcessorInterface;

/**
 * Set "category" property from file path if not specified
 */
class CategoryProcessor implements ProcessorInterface
{
    public static function isSupported(string $type, $value): bool
    {
        return is_a($type, Article::class, true)
            && is_null($value);
    }

    public function __invoke(array &$data, array $context): void
    {
        if (!static::isSupported($context['type'], $data['category'] ?? null)) {
            return;
        }

        $data['category'] = \basename($context['content']->getSlug());
    }
}
