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

        return (empty($title)) ? null : $title;
    }

    public function getMetaDescription()
    {
        $metaDescription = $this->crawler->filter('head > meta[name="description"]')->eq(0)->attr('content');

        return (empty($metaDescription)) ? null : $metaDescription;
    }

    public function getH1()
    {
        try {
            $h1 = $this->crawler->filter('h1')->first()->text();
        } catch (\Exception $e) {
            return null;
        }

        return (empty($h1)) ? null : $h1;
    }

    public function getTwitterPropertiesLevel()
    {
        $twitterProperties = [];

        foreach (self::TWITTER_PROPERTIES as $property) {
            try {
                $twitterProperties[$property] = $this->getProperty(sprintf('twitter:%s', $property));
            } catch (\Exception $e) {
                $twitterProperties[$property] = null;
            }
        }

        $twitterProperties = array_filter($twitterProperties);

        if (empty($twitterProperties)) {
            return 'missing';
        }

        return \count($twitterProperties) == 5 ? 'completed' : 'almost-completed';
    }

    public function getTwitterProperties()
    {
        $twitterProperties = [];

        foreach (self::TWITTER_PROPERTIES as $property) {
            try {
                $twitterProperties[$property] = $this->getProperty(sprintf('twitter:%s', $property));
            } catch (\Exception $e) {
                $twitterProperties[$property] = null;
            }
        }

        return array_filter($twitterProperties);
    }

    public function getOpenGraphProperties()
    {
        $openGraphProperties = [];

        foreach (self::OG_PROPERTIES as $property) {
            try {
                $openGraphProperties[$property] = $this->getProperty(sprintf('og:%s', $property));
            } catch (\Exception $e) {
                $openGraphProperties[$property] = null;
            }
        }

        return array_filter($openGraphProperties);
    }

    public function getOpenGraphLevel()
    {
        $openGraphProperties = [];

        foreach (self::OG_PROPERTIES as $property) {
            try {
                $openGraphProperties[$property] = $this->getProperty(sprintf('og:%s', $property));
            } catch (\Exception $e) {
                $openGraphProperties[$property] = null;
            }
        }

        $openGraphProperties = array_filter($openGraphProperties);

        if (empty($openGraphProperties)) {
            return 'missing';
        }

        return \count($openGraphProperties) == 5 ? 'completed' : 'almost-completed';
    }

    public function getProperty($property)
    {
        $meta = $this->crawler->filter(sprintf('head > meta[property="%s"]', $property))->eq(0)->attr('content');

        return (empty($meta)) ? null : $meta;
    }
}
