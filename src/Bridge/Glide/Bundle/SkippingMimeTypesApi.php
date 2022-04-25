<?php

declare(strict_types=1);

namespace App\Bridge\Glide\Bundle;

use League\Glide\Api\ApiInterface;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Mime\MimeTypesInterface;

/**
 * An API implementation allowing to skip Glide resize on some specific MIME types
 * (usually unsupported by Glide, or removing GIF animations for instance),
 * but still allowing to pass through Glide's server and move the images to a properly public location.
 */
class SkippingMimeTypesApi implements ApiInterface
{
    public function __construct(
        private ApiInterface $decorated,
        private array $skippedTypes = ['image/gif'],
        private MimeTypesInterface $types = new MimeTypes()
    ) {
    }

    public function run($source, array $params): string
    {
        if (\in_array($this->types->guessMimeType($source), $this->skippedTypes, true)) {
            return (string) file_get_contents($source);
        }

        return $this->decorated->run($source, $params);
    }
}
