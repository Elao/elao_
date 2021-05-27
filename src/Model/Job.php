<?php

declare(strict_types=1);

namespace App\Model;

class Job
{
    use MetaTrait;

    public const DEFAULT_SOCIAL_IMG = 'images/common/elao-developpe-du-lien.jpg';

    public string $slug;

    /** @var string[] An array of title part that'll be separated by a new line on show */
    public array $title;
    /** The short description shown on listing and as default SEO description if no $metaDescription is fulfilled */
    public string $description;
    public string $content;

    /** Date pour laquelle le poste est disponible */
    public ?\DateTimeInterface $hiringDate;
    public JobContractType $contractType;
    public string $place;
    public ?string $formation;
    public ?string $experience;
    public ?string $remuneration;

    public bool $active = false;

    public \DateTimeInterface $date;
    public ?\DateTimeInterface $lastModified;

    public function getFullTitle(): string
    {
        return implode(' ', $this->title);
    }

    public function __construct()
    {
        // Defaults to CDI
        $this->contractType = JobContractType::CDI();
        // Default social images
        $this->ogImage = $this->twitterImage = self::DEFAULT_SOCIAL_IMG;
    }
}
