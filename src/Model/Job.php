<?php

declare(strict_types=1);

namespace App\Model;

class Job
{
    public string $title;
    public string $slug;
    public string $content;
    public bool $active = false;
    public \DateTimeInterface $date;
    public \DateTimeInterface $lastModified;
}
