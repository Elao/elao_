<?php

declare(strict_types=1);

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

    public function __invoke(array &$data, string $type, Content $content): void
    {
        if (!is_a($type, Article::class, true) || !isset($data['author'])) {
            return;
        }

        $data['author'] = $this->contentManager->getContent(Member::class, $data['author']);
    }
}
