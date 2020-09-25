<?php

declare(strict_types=1);

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
        if (!is_a($type, Article::class, true)) {
            return;
        }

        if (!isset($data['authors']) && !isset($data['author'])) {
            throw new \Exception('At least one author must be specified.');
        }

        $authors = $data['authors'] ?? $data['author'];

        $data['authors'] = array_map(
            fn (string $id) => $this->contentManager->getContent(Member::class, $id),
            \is_array($authors) ? $authors : [$authors]
        );
    }
}
