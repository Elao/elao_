<?php

declare(strict_types=1);

namespace App\Model;

class Technology
{
    public string $name;
    public ?string $logo = null;
    public string $title;
    public string $slug;
    public string $content;
    public ?string $description;
    public ?array $articles = null;
    public ?array $caseStudies = null;
    public \DateTimeInterface $lastModified;

    /** Show a dedicated page or not */
    public bool $show = true;
}
