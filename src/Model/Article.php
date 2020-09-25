<?php

declare(strict_types=1);

namespace App\Model;

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
    public array $categories;
    //public string $category;

    /** @var array<Member> */
    public array $authors = [];

    public ?array $credits = null;

    /**
     * Automatically generated
     *
     * @var string[]
     */
    public ?array $tableOfContent = null;
}
