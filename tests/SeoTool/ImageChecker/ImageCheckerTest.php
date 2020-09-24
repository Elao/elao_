<?php

declare(strict_types=1);

use App\SEOTool\Checker\ImageChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class ImageCheckerTest extends TestCase
{
    public function testCountImages()
    {
        $imgChecker = $this->getImageChecker('images.html');

        static::assertEquals(2, $imgChecker->countAllImages());
    }

    public function testCountImagesWithAltTest()
    {
        $imgChecker = $this->getImageChecker('images.html');

        static::assertEquals(1, $imgChecker->countAltFromImages());
    }

    public function testCountZeroImage()
    {
        $imgChecker = $this->getImageChecker('no-images.html');

        static::assertEquals(0, $imgChecker->countAllImages());
    }

    public function testCountZeroImageWithAltTest()
    {
        $imgChecker = $this->getImageChecker('no-images.html');

        static::assertEquals(0, $imgChecker->countAltFromImages());
    }

    public function testExplicitIcons()
    {
        $imgChecker = $this->getImageChecker('explicit-icons.html');
        static::assertEquals(1, $imgChecker->countExplicitIcons());
    }

    public function testIcons()
    {
        $imgChecker = $this->getImageChecker('explicit-icons.html');
        static::assertEquals(2, $imgChecker->countIcons());
    }

    public function getImageChecker($filename): ImageChecker
    {
        $html = file_get_contents(sprintf('tests/SeoTool/ImageChecker/%s', $filename));
        $crawler = new Crawler($html);

        return new ImageChecker($crawler);
    }
}
