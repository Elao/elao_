<?php

declare(strict_types=1);

namespace App\Model;

class Member
{
    public string $slug;

    // Bio
    public string $name;
    public ?string $gender = null;

    /** Displayed rather than name in articles */
    public ?string $pseudo = null;

    public ?string $bio = null;

    /** Position in the company / job title */
    public ?string $position = null;

    // Social
    public ?string $website = null;
    public ?string $twitter = null;
    public ?string $github = null;
    public ?string $email = null;
    public ?string $avatar = null;
    public ?string $linkedIn = null;

    /** @var string[] */
    public array $certifications = [];

    public ?array $emojis = [];

    // Flags

    public bool $active = false;

    /** Vélotafeur */
    public bool $🚲 = false;

    /** Use an anonymous tribe picture */
    public bool $anonymousTribePicture = false;

    public ?\DateTime $integrationDate = null;
    public ?\DateTimeImmutable $lastModified = null;

    /**
     * Fields that requires to be initialized (not nullable, no default value) go in the constructor.
     */
    public function __construct(string $slug, string $name)
    {
        $this->name = $name;
        $this->slug = $slug;
    }

    public function getTribePicture(): string
    {
        return sprintf('images/members/%s.jpg', $this->anonymousTribePicture ? 'default' : $this->slug);
    }

    public function getAvatar(): ?string
    {
        return $this->avatar ?? 'images/authors/default.png';
    }
}
