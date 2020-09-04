<?php

namespace App\Model;

class CaseStudy {
    public string $title;
    public ?string $description;
    public string $slug;
    public string $content;
    public \DateTimeInterface $date;
    public \DateTimeInterface $lastModified;
}
