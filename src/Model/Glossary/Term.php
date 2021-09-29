<?php

declare(strict_types=1);

namespace App\Model\Glossary;

class Term
{
    public string $name;
    public ?string $logo = null;
    public array $title = [];
    public string $slug;
    public string $content;
    public ?string $description;
    public ?string $metaDescription = null;
    public ?string $titleSeo = null;
    public ?array $articles = null;
    public \DateTimeInterface $lastModified;

    /** Show a dedicated page or not */
    public bool $show = true;

    public function getFullTitle(): string
    {
        return implode(' ', $this->title);
    }
}
