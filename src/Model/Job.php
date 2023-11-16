<?php

declare(strict_types=1);

namespace App\Model;

class Job
{
    use MetaTrait;

    public const DEFAULT_SOCIAL_IMG = 'content/images/common/elao-developpe-du-lien.jpg';

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
    public ?int $experience;
    public ?int $remunerationMin = null;
    public ?int $remunerationMax = null;

    public bool $active = false;

    public \DateTimeInterface $date;
    public ?\DateTimeInterface $lastModified;

    public function getFullTitle(): string
    {
        return implode(' ', $this->title);
    }

    public function getFullRemuneration(): ?string
    {
        if (null !== $this->remunerationMin && null !== $this->remunerationMax) {
            return sprintf('%s - %s K€', $this->remunerationMin / 1000, $this->remunerationMax / 1000);
        }

        if (null !== $this->remunerationMin && null === $this->remunerationMax) {
            return sprintf('%s K€', $this->remunerationMin / 1000);
        }

        return null;
    }

    public function getExperienceInMonths(): ?string
    {
        if ($this->experience !== null) {
            return sprintf('%s', $this->experience * 12);
        }

        return null;
    }

    public function __construct()
    {
        // Defaults to CDI
        $this->contractType = JobContractType::CDI;
        // Default social images
        $this->ogImage = $this->twitterImage = self::DEFAULT_SOCIAL_IMG;
    }
}
