<?php

declare(strict_types=1);

namespace App\SEOTool\Checker;

class Headline
{
    /** @var int */
    public $level;

    /** @var string|null */
    public $content;

    /** @var array|null */
    public $children;

    /** @var Headline */
    public $parent;

    public function __construct(int $level, ?string $content)
    {
        $this->level = $level;
        $this->content = $content;
    }

    public function addChild(Headline $headline)
    {
        $this->children[] = $headline;
        $headline->setParent($this);
    }

    public function setParent(Headline $parent)
    {
        $this->parent = $parent;
    }

    public function hasChildren()
    {
        return \count($this->children);
    }

    public function getContent(): ?string
    {
//        return preg_replace( "/\r|\n/", "", $this->content );
//        return str_replace(array("\r", "\n"), '', $this->content);
        return str_replace(['  '], '', $this->content);
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getHn(): string
    {
        return sprintf('h%d', $this->level);
    }
}
