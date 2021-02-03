<?php

declare(strict_types=1);

namespace App\Model;

class CaseStudy
{
    public ?string $metaTitle = null;
    public ?string $metaDescription = null;
    public string $title;
    public ?string $description;
    public string $slug;
    public string $content;
    public \DateTimeInterface $date;
    public \DateTimeInterface $lastModified;
    public string $caseUrl;
    public string $clients;
    public string $users;
    public string $size;
    public array $services = [];
    public array $images = [];

    /** @var array<Technology> */
    public array $technologies = [];

    /** @var array<Member> */
    public array $authors = [];
}
