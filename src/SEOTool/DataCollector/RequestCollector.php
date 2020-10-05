<?php

declare(strict_types=1);

namespace App\SEOTool\DataCollector;

use App\SEOTool\Checker\AccessibilityChecker;
use App\SEOTool\Checker\ImageChecker;
use App\SEOTool\Checker\OptimizationChecker;
use App\SEOTool\Checker\RobotDirectivesChecker;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;

class RequestCollector extends DataCollector implements LateDataCollectorInterface
{
    /** @var ImageChecker */
    public $imageChecker;

    /** @var OptimizationChecker */
    public $optimizationChecker;

    /** @var AccessibilityChecker */
    public $accessbilityChecker;

    /** @var RobotDirectivesChecker */
    public $robotDirectivesChecker;

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $this->imageChecker = new ImageChecker(new Crawler((string) $response->getContent(), 'text/html'));
        $this->optimizationChecker = new OptimizationChecker(new Crawler((string) $response->getContent(), 'text/html'));
        $this->accessbilityChecker = new AccessibilityChecker(new Crawler((string) $response->getContent(), 'text/html'));

        $this->data = [
            'response' => $response,
            'title' => $this->optimizationChecker->getTitle(),
            'metaDescription' => $this->optimizationChecker->getMetaDescription(),
            'h1' => $this->optimizationChecker->getH1(),
            'oneH1' => $this->optimizationChecker->isOneH1(),
            'OpenGraphLevel' => $this->optimizationChecker->getOpenGraphLevel(),
            'twitterPropertiesLevel' => $this->optimizationChecker->getTwitterPropertiesLevel(),
            'twitterProperties' => $this->optimizationChecker->getTwitterProperties(),
            'OpenGraphProperties' => $this->optimizationChecker->getOpenGraphProperties(),
            'missingOpenGraphProperties' => $this->optimizationChecker->getMissingOGProperties(),
            'missingTwitterProperties' => $this->optimizationChecker->getMissingTwitterProperties(),
            'countHeadlines' => $this->accessbilityChecker->countHeadlinesByHn(),
            'isHeader' => $this->accessbilityChecker->isHeader(),
            'isAside' => $this->accessbilityChecker->isAside(),
            'isNavInHeader' => $this->accessbilityChecker->isNavInHeader(),
            'isFooter' => $this->accessbilityChecker->isFooter(),
            'isArticle' => $this->accessbilityChecker->isArticle(),
            'isHeaderInArticle' => $this->accessbilityChecker->isHeaderInArticle(),
            'countAllImages' => $this->imageChecker->countAllImages(),
            'countAltFromImages' => $this->imageChecker->countAltFromImages(),
            'listMissingAltFromImages' => $this->imageChecker->listImagesWhithoutAlt(),
            'listNonExplicitIcons' => $this->imageChecker->listNonExplicitIcons(),
            'countAllIcons' => $this->imageChecker->countIcons(),
            'countAllExplicitIcons' => $this->imageChecker->countExplicitIcons(),
            'headlinesTree' => $this->accessbilityChecker->getHeadlineTree(),
        ];
    }

    public function lateCollect(): void
    {
        $response = $this->data['response'];
        $this->robotDirectivesChecker = new RobotDirectivesChecker(new Crawler((string) $response->getContent(), 'text/html'), $response);
        $this->data['XRobotsTag'] = $this->robotDirectivesChecker->getXRobotsTag();
        $this->data['canonical'] = $this->robotDirectivesChecker->getCanonical();
        $this->data['metaRobot'] = $this->robotDirectivesChecker->getMetaRobotsTag();
        $this->data['metaGooglebot'] = $this->robotDirectivesChecker->getMetaGooglebotsTag();
        $this->data['metaGooglebotNews'] = $this->robotDirectivesChecker->getMetaGooglebotNewsTag();
        $this->data['language'] = $this->robotDirectivesChecker->getLanguage();
    }

    public function reset(): void
    {
        $this->data = [];
    }

    public function getHeadlinesTree(): array
    {
        return $this->data['headlinesTree'];
    }

    public function getName(): string
    {
        return 'app.request_collector';
    }

    public function getLanguage(): ?string
    {
        return $this->data['language'];
    }

    public function getCountHeadlines(): array
    {
        return $this->data['countHeadlines'];
    }

    public function listMissingAltFromImages(): array
    {
        return $this->data['listMissingAltFromImages'];
    }

    public function listNonExplicitIcons(): array
    {
        return $this->data['listNonExplicitIcons'];
    }

    public function getMissingTwitterProperties(): array
    {
        return $this->data['missingTwitterProperties'];
    }

    public function getMissingOpenGraphProperties(): array
    {
        return $this->data['missingOpenGraphProperties'];
    }

    public function getOneH1(): bool
    {
        return $this->data['oneH1'];
    }

    public function getOpenGraphProperties(): array
    {
        return $this->data['OpenGraphProperties'];
    }

    public function getTwitterProperties(): array
    {
        return $this->data['twitterProperties'];
    }

    public function getCountAllIcons(): int
    {
        return $this->data['countAllIcons'];
    }

    public function getCountAllExplicitIcons(): int
    {
        return $this->data['countAllExplicitIcons'];
    }

    public function getTitle(): ?string
    {
        return $this->data['title'];
    }

    public function getH1(): ?string
    {
        return $this->data['h1'];
    }

    public function getMetaDescription(): ?string
    {
        return $this->data['metaDescription'];
    }

    public function getOpenGraphLevel(): string
    {
        return $this->data['OpenGraphLevel'];
    }

    public function getTwitterPropertiesLevel(): string
    {
        return $this->data['twitterPropertiesLevel'];
    }

    public function getCountAllImages(): int
    {
        return $this->data['countAllImages'];
    }

    public function getCountAltFromImages(): int
    {
        return $this->data['countAltFromImages'];
    }

    public function getXRobotsTag(): ?string
    {
        return $this->data['XRobotsTag'];
    }

    public function getCanonical(): ?string
    {
        return $this->data['canonical'];
    }

    public function getMetaRobot(): ?string
    {
        return $this->data['metaRobot'];
    }

    public function getMetaGooglebot(): ?string
    {
        return $this->data['metaGooglebot'];
    }

    public function getMetaGooglebotNews(): ?string
    {
        return $this->data['metaGooglebotNews'];
    }
}
