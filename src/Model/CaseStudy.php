<?php

declare(strict_types=1);

namespace App\Model;

class CaseStudy
{
    use MetaTrait;

    public string $title;
    public ?string $description = null;
    public ?string $shortDescription = null;
    public string $slug;
    public string $content;
    public \DateTimeInterface $date;
    public \DateTimeInterface $lastModified;
    public ?string $websiteUrl = null;
    public string $clients;
    public string $size;
    public array $services = [];
    public array $images = [];
    public array $technologies = [];
    public array $members = [];
    public bool $enabled = true;

    public function hasMember(Member $member): bool
    {
        return \in_array($member->slug, $this->members, true);
    }

    public function hasTechnology(Technology $technology): bool
    {
        return \in_array($technology->slug, $this->technologies, true);
    }
}
