<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use App\Model\Article;
use Stenope\Bundle\Behaviour\ContentManagerAwareInterface;
use Stenope\Bundle\Behaviour\ContentManagerAwareTrait;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Sanitize tag list
 */
class TagProcessor implements ProcessorInterface, ContentManagerAwareInterface
{
    use ContentManagerAwareTrait;

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function __invoke(array &$data, string $type, Content $content): void
    {
        if (!is_a($type, Article::class, true)) {
            return;
        }

        $data['tags'] = array_map([$this, 'slugify'], $data['tags']);
    }

    private function slugify(string $tag): string
    {
        return $this->slugger->slug($tag)->lower()->toString();
    }
}
