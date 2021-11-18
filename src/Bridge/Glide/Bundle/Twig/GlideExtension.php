<?php

declare(strict_types=1);

namespace App\Bridge\Glide\Bundle\Twig;

use App\Bridge\Glide\Bundle\ResizedUrlGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class GlideExtension extends AbstractExtension
{
    private ResizedUrlGenerator $resizedUrlGenerator;

    public function __construct(ResizedUrlGenerator $resizer)
    {
        $this->resizedUrlGenerator = $resizer;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('glide_image_resize', \Closure::fromCallable([$this, 'resizeImage'])),
            new TwigFilter('glide_image_preset', \Closure::fromCallable([$this, 'resizeImageWithPreset'])),
        ];
    }

    public function resizeImageWithPreset(string $filename, string $preset, array $options = []): string
    {
        return $this->resizedUrlGenerator->withPreset($filename, $preset, $options);
    }

    public function resizeImage(string $filename, array $options): string
    {
        return $this->resizedUrlGenerator->withOptions($filename, $options);
    }
}
