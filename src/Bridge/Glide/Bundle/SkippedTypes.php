<?php

namespace App\Bridge\Glide\Bundle;

use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Mime\MimeTypesInterface;

class SkippedTypes
{
    public function __construct(
        private array $skippedTypes = ['image/gif'],
        private MimeTypesInterface $types = new MimeTypes()
    ) {
    }

    public function isSkippedUrl(string $url): bool
    {
        return 0 !== \count(\array_intersect(
            $this->types->getMimeTypes(pathinfo($url, PATHINFO_EXTENSION)),
            $this->skippedTypes,
        ));
    }

    public function isSkippedFile(string $path): bool
    {
        return \in_array($this->types->guessMimeType($path), $this->skippedTypes, true);
    }
}
