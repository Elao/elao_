<?php

/*
 * This file is part of the "Tom32i/Content" bundle.
 *
 * @author Thomas Jarrand <thomas.jarrand@gmail.com>
 */

namespace App\Content\Processor;

use App\Model\Article;
use App\Model\Member;
use Content\Behaviour\ProcessorInterface;

/**
 * Fetch author form Member list
 */
class AuthorProcessor implements ProcessorInterface
{
    public static function isSupported(string $type, $value): bool
    {
        return is_a($type, Article::class, true)
            && !is_null($value)
            && is_string($value);
    }

    public function __invoke(array &$data, array $context): void
    {
        if (!static::isSupported($context['type'], $data['author'] ?? null)) {
            return;
        }

        $data['author'] = $context['contentManager']->getContent(Member::class, $data['author']);
    }
}
