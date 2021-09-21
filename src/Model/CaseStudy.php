<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Glossary\Term;

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
    public array $terms = [];
    public array $members = [];
    public bool $enabled = true;

    public function hasMember(Member $member): bool
    {
        return \in_array($member->slug, $this->members, true);
    }

    public function hasTerm(Term $term): bool
    {
        return \in_array($term->slug, $this->terms, true);
    }
}
