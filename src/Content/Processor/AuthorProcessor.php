<?php

/*
 * This file is part of the "Tom32i/Content" bundle.
 *
 * @author Thomas Jarrand <thomas.jarrand@gmail.com>
 */

namespace App\Content\Processor;

use App\Model\Article;
use App\Model\Member;
use Content\Behaviour\ContentManagerAwareInterface;
use Content\Behaviour\ContentManagerAwareTrait;
use Content\Behaviour\ProcessorInterface;
use Content\Content;

/**
 * Fetch author form Member list
 */
class AuthorProcessor implements ProcessorInterface, ContentManagerAwareInterface
{
    use ContentManagerAwareTrait;

    public static function isSupported(string $type, $value): bool
    {
        return is_a($type, Article::class, true)
            && !is_null($value)
            && is_string($value);
    }

    public function __invoke(array &$data, string $type, Content $content): void
    {
        if (!static::isSupported($type, $data['author'] ?? null)) {
            return;
        }

        $data['author'] = $this->contentManager->getContent(Member::class, $data['author']);
    }
}
