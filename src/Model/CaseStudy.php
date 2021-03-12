<?php

declare(strict_types=1);

namespace App\Model;

class CaseStudy
{
    use MetaTrait;

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
    public array $technologies = [];
    public array $members = [];
    public bool $enabled = true;

    public function hasMember(Member $member): bool
    {
        return \in_array($member->slug, $this->members, true);
    }
}
