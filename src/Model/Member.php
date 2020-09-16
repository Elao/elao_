<?php

declare(strict_types=1);

namespace App\Model;

class Member
{
    public string $slug;

    // Bio
    public string $name;
    public ?string $shortBio = null;
    public ?string $longBio = null;
    public ?string $position = null;

    // Social
    public ?string $website = null;
    public ?string $twitter = null;
    public ?string $github = null;
    public ?string $email = null;
    public ?string $avatar = null;

    public ?\DateTimeImmutable $lastModified = null;

    public function __construct(
        string $slug,
        string $name,
        ?string $shortBio = null,
        ?string $longBio = null,
        ?string $position = null,
        ?string $website = null,
        ?string $twitter = null,
        ?string $github = null,
        ?string $email = null,
        ?string $avatar = null
    ) {
        $this->slug = $slug;
        $this->name = $name;
        $this->shortBio = $shortBio;
        $this->longBio = $longBio;
        $this->position = $position;
        $this->website = $website;
        $this->twitter = $twitter;
        $this->github = $github;
        $this->email = $email;
        $this->avatar = $avatar;
    }
}
