<?php

declare(strict_types=1);

namespace App\Model;

class Certification
{
    public string $slug;
    public string $title;
    public ?string $logo = null;

    public function getLogoPath(): string
    {
        return null !== $this->logo
            ? "build/images/certifications/{$this->logo}"
            : 'build/images/certifications/default.svg'
        ;
    }
}
