<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Router\ExternalUrlGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Extension to generate an external URL using the Symfony Router, omitting the current request context.
 */
class ExternalUrlExtension extends AbstractExtension
{
    private ExternalUrlGenerator $urlGenerator;

    public function __construct(ExternalUrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('external_url', [$this, 'generateExternalUrl']),
        ];
    }

    public function generateExternalUrl(string $route, array $params = []): string
    {
        return $this->urlGenerator->generate($route, $params);
    }
}
