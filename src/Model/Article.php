<?php

declare(strict_types=1);

namespace App\Model;

use App\Stenope\Processor\GithubEditLinkProcessor;
use Stenope\Bundle\Processor\TableOfContentProcessor;
use Stenope\Bundle\TableOfContent\TableOfContent;

class Article
{
    public string $type;
    public string $title;
    public string $slug;
    public string $content;
    public \DateTimeInterface $date;
    public ?\DateTimeInterface $lastModified = null;
    public ?string $description;
    /**
     * Main image for the articles, used as thumbnail and banner.
     */
    public string $thumbnail;
    /**
     * If provided, the image to use on top of the show article view instead of the thumbnail image.
     */
    public ?string $banner = null;
    public array $tags = [];

    /**
     * Used for old articles written in english
     */
    public string $lang = 'fr';

    /** @var array<Member> */
    public array $authors = [];

    public ?array $credits = null;

    /**
     * Automatically generated
     *
     * @see TableOfContentProcessor
     */
    public ?TableOfContent $tableOfContent = null;

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

    public function getLastModifiedOrCreated(): \DateTimeInterface
    {
        return $this->lastModified ?? $this->date;
    }
}
