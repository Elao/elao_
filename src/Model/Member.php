<?php

declare(strict_types=1);

namespace App\Model;

class Member
{
    public string $slug;
    public bool $active;
    public ?\DateTime $integrationDate;

    // Bio
    public string $name;
    public ?string $pseudo;
    public ?string $shortBio = null;
    public ?string $longBio = null;
    public ?string $position = null;

    // Social
    public ?string $website = null;
    public ?string $twitter = null;
    public ?string $github = null;
    public ?string $email = null;
    public ?string $avatar = null;
    public ?string $linkedIn = null;

    public array $certifications = [];
    public ?\DateTimeImmutable $lastModified = null;
    public ?array $emojis;

    // Flags

    /** @var bool Vélotafeur */
    public bool $🚲 = false;

    public function __construct(
        string $slug,
        string $name,
        bool $active = false,
        ?string $pseudo = null,
        ?string $shortBio = null,
        ?string $longBio = null,
        ?string $position = null,
        ?string $website = null,
        ?string $twitter = null,
        ?string $github = null,
        ?string $email = null,
        ?string $avatar = null,
        ?string $linkedIn = null,
        array $certifications = [],
        ?\DateTime $integrationDate = null,
        ?array $emojis = [],
        bool $🚲 = false
    ) {
        $this->slug = $slug;
        $this->name = $name;
        $this->active = $active;
        $this->pseudo = $pseudo;
        $this->shortBio = $shortBio;
        $this->longBio = $longBio;
        $this->position = $position;
        $this->website = $website;
        $this->twitter = $twitter;
        $this->github = $github;
        $this->email = $email;
        $this->avatar = $avatar;
        $this->linkedIn = $linkedIn;
        $this->certifications = $certifications;
        $this->integrationDate = $integrationDate;
        $this->emojis = $emojis;
        $this->🚲 = $🚲;
    }
}
