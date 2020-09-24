<?php

declare(strict_types=1);

namespace App\SEOTool\Checker;

use Symfony\Component\DomCrawler\Crawler;

class OptimizationChecker
{
    /** @var Crawler */
    private $crawler;

    const TWITTER_PROPERTIES = ['card', 'title', 'description', 'site', 'creator'];

    const OG_PROPERTIES = ['title', 'locale', 'description', 'url', 'site_name'];

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function getTitle(): ?string
    {
        $title = $this->crawler->filter('head > title')->text();

        if ($title === '') {
            return null;
        }

        return $title;
    }

    public function getMetaDescription(): ?string
    {
        $metaDescription = $this->crawler->filter('head > meta[name="description"]')->first()->attr('content');

        if ($metaDescription === '') {
            return null;
        }

        return $metaDescription;
    }

    public function getH1(): ?string
    {
        try {
            $h1 = $this->crawler->filter('h1')->first()->text();
        } catch (\Exception $e) {
            return null;
        }

        if ($h1 === '') {
            return null;
        }

        return $h1;
    }

    public function getTwitterPropertiesLevel(): ?string
    {
        $twitterProperties = $this->getProperties('twitter', self::TWITTER_PROPERTIES);

        if (0 === \count($twitterProperties)) {
            return 'missing';
        }

        return \count($twitterProperties) == 5 ? 'completed' : 'almost-completed';
    }

    public function getTwitterProperties(): array
    {
        return $twitterProperties = $this->getProperties('twitter', self::TWITTER_PROPERTIES);
    }

    public function getOpenGraphProperties(): array
    {
        return $this->getProperties('og', self::OG_PROPERTIES);
    }

    public function getOpenGraphLevel(): ?string
    {
        $openGraphProperties = $this->getProperties('og', self::OG_PROPERTIES);

        if (0 === \count($openGraphProperties)) {
            return 'missing';
        }

        return \count($openGraphProperties) == 5 ? 'completed' : 'almost-completed';
    }

    public function getProperty(string $property): ?string
    {
        $meta = $this->crawler->filter(sprintf('head > meta[property="%s"]', $property))->eq(0)->attr('content');

        if ($meta === '') {
            return null;
        }

        return $meta;
    }

    public function getProperties(string $type, array $properties): array
    {
        $propertiesCompleted = [];

        foreach ($properties as $property) {
            try {
                $propertiesCompleted[$property] = $this->getProperty(sprintf('%s:%s', $type, $property));
            } catch (\Exception $e) {
                $propertiesCompleted[$property] = null;
            }
        }

        return array_filter($propertiesCompleted);
    }
}
