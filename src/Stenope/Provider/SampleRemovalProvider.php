<?php

declare(strict_types=1);

namespace App\Stenope\Provider;

use Stenope\Bundle\Content;
use Stenope\Bundle\Provider\ContentProviderInterface;
use Stenope\Bundle\Provider\ReversibleContentProviderInterface;
use Stenope\Bundle\ReverseContent\Context;

/**
 * Decorator for excluding some specific contents if enabled.
 */
class SampleRemovalProvider implements ReversibleContentProviderInterface
{
    private ContentProviderInterface $provider;
    private bool $disabled;
    /** @var string[] Ignored slugs */
    private array $ignored;

    public function __construct(ContentProviderInterface $provider, bool $disabled, array $ignored = [])
    {
        $this->provider = $provider;
        $this->disabled = $disabled;
        $this->ignored = $ignored;
    }

    public function listContents(): iterable
    {
        $contents = $this->provider->listContents();

        if ($this->disabled) {
            yield from $contents;

            return;
        }

        foreach ($contents as $content) {
            if (\in_array($content->getSlug(), $this->ignored, true)) {
                continue;
            }

            yield $content;
        }
    }

    public function getContent(string $slug): ?Content
    {
        if (!$this->disabled && \in_array($slug, $this->ignored, true)) {
            return null;
        }

        return $this->provider->getContent($slug);
    }

    public function supports(string $className): bool
    {
        return $this->provider->supports($className);
    }

    public function reverse(Context $context): ?Content
    {
        if ($this->provider instanceof ReversibleContentProviderInterface) {
            return $this->provider->reverse($context);
        }

        return null;
    }
}
