<?php

declare(strict_types=1);

namespace App\Model;

use App\Stenope\Processor\GithubEditLinkProcessor;
use Stenope\Bundle\Processor\TableOfContentProcessor;
use Stenope\Bundle\TableOfContent\Headline;

class Article
{
    public string $type;
    public string $title;
    public string $slug;
    public string $content;
    public ?\DateTimeImmutable $date = null;
    public ?\DateTimeImmutable $lastModified = null;
    public bool $draft = true;
    public ?string $description;
    /**
     * Main image for the articles, used as thumbnail and banner.
     */
    public string $thumbnail;
    /**
     * If provided, the image to use on top of the show article view instead of the thumbnail image.
     */
    public ?string $banner;
    public array $tags = [];
    public string $lang = 'fr';
    public array $categories;
    //public string $category;

    /** @var array<Member> */
    public array $authors = [];

    public ?array $credits = null;

    /**
     * Automatically generated
     *
     * @var Headline[]
     *
     * @see TableOfContentProcessor
     */
    public array $tableOfContent = [];

    /**
     * True if the article is obsolete/outdated.
     * A string to use a custom disclaimer.
     *
     * @var bool|string
     */
    public $outdated = false;

    public ?string $tweetId = null;

    /** @see GithubEditLinkProcessor */
    public ?string $githubEditLink = null;

    public function hasTag(string $tag): bool
    {
        return \in_array($tag, $this->tags, true);
    }

    public function hasAuthor(Member $author, int $maxAuthors = 0): bool
    {
        if ($maxAuthors > 0 && \count($this->authors) > $maxAuthors) {
            return false;
        }

        return \in_array($author, $this->authors, true);
    }

    public function getLastModifiedOrCreated(): ?\DateTimeImmutable
    {
        return $this->lastModified ?? $this->date;
    }
}
