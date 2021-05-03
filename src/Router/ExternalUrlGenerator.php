<?php

declare(strict_types=1);

namespace App\Router;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * Generates an external URL using the Symfony Router, omitting the current request context.
 */
class ExternalUrlGenerator
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function generate(string $route, array $params = []): string
    {
        $prevContext = $this->urlGenerator->getContext();

        try {
            // use an empty request context since the route must provide everything:
            $this->urlGenerator->setContext(new RequestContext());

            return $this->urlGenerator->generate($route, $params, UrlGeneratorInterface::ABSOLUTE_URL);
        } finally {
            $this->urlGenerator->setContext($prevContext);
        }
    }
}
