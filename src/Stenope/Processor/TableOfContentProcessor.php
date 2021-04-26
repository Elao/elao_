<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Build the article table of content from its titles
 */
class TableOfContentProcessor implements ProcessorInterface
{
    public const MAX_DEPTH = 6;
    public const MIN_DEPTH = 1; // Don't account for h1 by default

    private string $tableOfContentProperty;
    private string $contentProperty;
    /** Default depth when using `tableOfContent: true` */
    private int $defaultDepth;

    public function __construct(
        string $tableOfContentProperty = 'tableOfContent',
        string $contentProperty = 'content',
        int $defaultDepth = self::MAX_DEPTH
    ) {
        $this->tableOfContentProperty = $tableOfContentProperty;
        $this->contentProperty = $contentProperty;
        $this->defaultDepth = $defaultDepth;
    }

    public function __invoke(array &$data, string $type, Content $content): void
    {
        if (!isset($data[$this->tableOfContentProperty]) || !$data[$this->tableOfContentProperty]) {
            return;
        }

        $crawler = new Crawler($data[$this->contentProperty]);

        try {
            $crawler->html();
        } catch (\Exception $e) {
            // Content is not valid HTML.
            return;
        }

        $value = $data[$this->tableOfContentProperty];
        $depth = \is_int($value) ? min($value, static::MAX_DEPTH) : min($this->defaultDepth, static::MAX_DEPTH);
        $titles = implode(', ', array_map(fn ($index) => 'h' . ($index + 1), array_keys(array_fill(static::MIN_DEPTH, $depth - static::MIN_DEPTH, null))));
        $tableOfContent = [];

        $previous = null;
        /* @var \DOMElement $element */
        foreach ($crawler->filter($titles) as $element) {
            \assert($element instanceof \DOMElement);
            $level = (int) $element->tagName[1];
            $current = new Headline($level, $element->getAttribute('id'), $element->textContent);
            $parent = $previous !== null ? $previous->getParentForLevel($level) : null;
            $previous = $current;

            if ($parent === null) {
                $tableOfContent[] = $current;
                continue;
            }

            $parent->addChild($current);
        }

        $data[$this->tableOfContentProperty] = $tableOfContent;
    }
}

class Headline
{
    public int $level;
    public ?string $content;
    private ?string $id;
    /** @var Headline[] */
    public array $children = [];
    public ?Headline $parent = null;

    public function __construct(int $level, ?string $id, ?string $content)
    {
        $this->level = $level;
        $this->content = $content;
        $this->id = $id;
    }

    public function addChild(Headline $headline): void
    {
        $this->children[] = $headline;
        $headline->setParent($this);
    }

    public function setParent(Headline $parent): void
    {
        $this->parent = $parent;
    }

    public function hasChildren(): bool
    {
        return \count($this->children) > 0;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getHn(): string
    {
        return sprintf('h%d', $this->level);
    }

    public function isParent(): bool
    {
        return $this->parent !== null;
    }

    public function getParent(): ?Headline
    {
        return $this->parent;
    }

    public function getParentForLevel(int $level): ?Headline
    {
        if ($this->level < $level) {
            return $this;
        }

        if ($this->parent === null) {
            return null;
        }

        return $this->parent->getParentForLevel($level);
    }
}
