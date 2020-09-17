<?php

declare(strict_types=1);

/*
 * This file is part of the "Tom32i/Content" bundle.
 *
 * @author Thomas Jarrand <thomas.jarrand@gmail.com>
 */

namespace App\Content\Processor;

use App\Model\Article;
use Content\Behaviour\ProcessorInterface;
use Content\Content;

/**
 * Set "category" property from file path if not specified
 */
class CategoryProcessor implements ProcessorInterface
{
    public function __invoke(array &$data, string $type, Content $content): void
    {
        if (!is_a($type, Article::class, true) || isset($data['category'])) {
            return;
        }

        $data['category'] = \basename($content->getSlug());
    }
}
