<?php

declare(strict_types=1);

namespace App\Model;

use App\Stenope\Processor\AuthorProcessor;
use App\Stenope\Processor\GithubEditLinkProcessor;
use Stenope\Bundle\Attribute\SuggestedDebugQuery;
use Stenope\Bundle\Processor\TableOfContentProcessor;
use Stenope\Bundle\TableOfContent\TableOfContent;

#[SuggestedDebugQuery('Scheduled', filters: 'not _.isPublished()', orders: 'desc:date')]
#[SuggestedDebugQuery('Outdated', filters: '_.outdated', orders: 'desc:date')]
#[SuggestedDebugQuery('Written in english', filters: '_.lang == "en"', orders: 'desc:date')]
#[SuggestedDebugQuery('By author', filters: "'tjarrand' in keys(_.authors)", orders: 'desc:date')]
#[SuggestedDebugQuery('By tag', filters: "'symfony' in _.tags", orders: 'desc:date')]
#[SuggestedDebugQuery('Matching slug', filters: "_.slug matches '/symfony/'", orders: 'desc:date')]
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

    /** @var string[] */
    public array $tags = [];

    /**
     * Used for old articles written in english
     */
    public string $lang = 'fr';

    /**
     * @var array<string,Member> Indexed by author slug
     *
     * @see AuthorProcessor
     */
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

    public function isPublished(): bool
    {
        return new \DateTimeImmutable() >= $this->date;
    }

    /**
     * Authors array (without keys)
     *
     * @return Member[]
     */
    public function getAuthorsArray(): array
    {
        return array_values($this->authors);
    }
}
