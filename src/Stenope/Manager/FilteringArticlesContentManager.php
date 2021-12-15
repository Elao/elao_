<?php

declare(strict_types=1);

namespace App\Stenope\Manager;

use App\Model\Article;
use Stenope\Bundle\Content;
use Stenope\Bundle\ContentManagerInterface;
use Stenope\Bundle\Exception\ContentNotFoundException;
use Stenope\Bundle\ReverseContent\Context;

/**
 * A content manager decorator, filtering unpublished yet articles according to a flag.
 */
class FilteringArticlesContentManager implements ContentManagerInterface
{
    private ContentManagerInterface $inner;
    private bool $filterUnpublished;

    public function __construct(ContentManagerInterface $contentManager, bool $filterUnpublished)
    {
        $this->inner = $contentManager;
        $this->filterUnpublished = $filterUnpublished;
    }

    public function getContents(string $type, $sortBy = null, $filterBy = null): array
    {
        $contents = $this->inner->getContents($type, $sortBy, $filterBy);

        if (!$this->filterUnpublished || $type !== Article::class) {
            return $contents;
        }

        /* @phpstan-ignore-next-line */
        return array_filter($contents, static fn (Article $article): bool => $article->isPublished());
    }

    public function getContent(string $type, string $id): object
    {
        if (!$this->filterUnpublished || $type !== Article::class) {
            return $this->inner->getContent($type, $id);
        }

        /** @var Article $content */
        $content = $this->inner->getContent($type, $id);

        if ($content->isPublished()) {
            return $content;
        }

        throw new ContentNotFoundException($type, $id);
    }

    public function reverseContent(Context $context): ?Content
    {
        return $this->inner->reverseContent($context);
    }

    public function supports(string $type): bool
    {
        return $this->inner->supports($type);
    }
}
