<?php

namespace App\Model;

class CaseStudy {
    public string $title;
    public ?string $description;
    public string $slug;
    public string $content;
    public \DateTimeInterface $created;
    public \DateTimeInterface $lastModified;

    public function __construct(
        string $title,
        ?string $description,
        string $slug,
        string $content,
        \DateTimeInterface $created,
        \DateTimeInterface $lastModified
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->slug = $slug;
        $this->content = $content;
        $this->created = $created;
        $this->lastModified = $lastModified;
    }
}
