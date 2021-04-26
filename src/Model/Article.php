<?php

declare(strict_types=1);

namespace App\Model;

use App\Stenope\Processor\Headline;

class Article
{
    public string $type;
    public string $title;
    public string $slug;
    public string $content;
    public ?\DateTimeImmutable $date = null;
    public ?\DateTimeImmutable $publishdate = null;
    public \DateTimeImmutable $lastModified;
    public bool $draft = true;
    public ?string $description;
    public string $thumbnail;
    public string $header;
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
     */
    public ?array $tableOfContent = null;

    /**
     * True if the article is obsolete/outdated.
     * A string to use a custom disclaimer.
     *
     * @var bool|string
     */
    public $outdated = false;

    public ?string $tweetId = null;

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
}
