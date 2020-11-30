<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use App\Model\Article;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;

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
