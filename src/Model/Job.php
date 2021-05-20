<?php

declare(strict_types=1);

namespace App\Model;

class Job
{
    use MetaTrait;

    public string $slug;

    /** @var string[] An array of title part that'll be separated by a new line on show */
    public array $title;
    /** The short description shown on listing and as default SEO description if no $metaDescription is fulfilled */
    public string $description;
    public string $content;

    public bool $active = false;

    public \DateTimeInterface $date;
    public ?\DateTimeInterface $lastModified;

    public function getFullTitle(): string
    {
        return implode(' ', $this->title);
    }
}
