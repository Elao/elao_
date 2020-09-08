<?php

namespace App\Content\Provider;

use App\Model\Article;
use Content\Behaviour\ContentProviderInterface;

class ArticleProvider implements ContentProviderInterface
{
    public function getDirectory(): string
    {
        return 'blog';
    }

    public function supports(string $className): bool
    {
        return is_a($className, Article::class, true);
    }
}
