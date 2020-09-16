<?php

declare(strict_types=1);

namespace App\Model;

class CaseStudy
{
    public string $title;
    public ?string $description;
    public string $slug;
    public string $content;
    public \DateTimeInterface $date;
    public \DateTimeInterface $lastModified;
}
