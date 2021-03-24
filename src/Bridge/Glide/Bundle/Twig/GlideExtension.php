<?php

declare(strict_types=1);

namespace App\Bridge\Glide\Bundle\Twig;

use App\Bridge\Glide\Bundle\GlideUrlBuilder;
use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Server;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class GlideExtension extends AbstractExtension
{
    private Server $server;
    private GlideUrlBuilder $glideUrlBuilder;
    private array $presetsNames;
    private bool $preGenerate;
    private LoggerInterface $logger;

    public function __construct(
        Server $server,
        GlideUrlBuilder $glideUrlBuilder,
        array $presetsNames = [],
        bool $preGenerate = false,
        ?LoggerInterface $logger = null
    ) {
        $this->server = $server;
        $this->glideUrlBuilder = $glideUrlBuilder;
        $this->presetsNames = $presetsNames;
        $this->preGenerate = $preGenerate;
        $this->logger = $logger ?? new NullLogger();
    }

    public function getFilters()
    {
        return [
            new TwigFilter('glide_image_resize', \Closure::fromCallable([$this, 'resizeImage'])),
            new TwigFilter('glide_image_preset', \Closure::fromCallable([$this, 'resizeImageWithPreset'])),
        ];
    }

    public function resizeImageWithPreset(string $filename, string $preset, array $options = []): string
    {
        if (!\in_array($preset, $this->presetsNames, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Preset "%s" does not exists. Known presets are %s',
                $preset,
                json_encode($this->presetsNames),
            ));
        }

        return $this->resizeImage($filename, ['p' => $preset] + $options);
    }

    public function resizeImage(string $filename, array $options): string
    {
        if (!$this->preGenerate) {
            // In case no pre-generation is asked, do only generate a link to the resize controller:
            return $this->glideUrlBuilder->buildUrl($filename, $options);
        }

        try {
            // Otherwise, pre-create the image in cache, and generate an URL to its public cache path:
            return $this->glideUrlBuilder->getPublicCachePath($this->server->makeImage($filename, $options));
        } catch (FileNotFoundException $exception) {
            $this->logger->warning('Image at path "{path}" not found', [
                'path' => $filename,
                'glide_options' => $options,
            ]);

            return '';
        }
    }
}
