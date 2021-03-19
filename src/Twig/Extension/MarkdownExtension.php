<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use Stenope\Bundle\Service\Parsedown;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MarkdownExtension extends AbstractExtension
{
    private Parsedown $parsedown;

    public function __construct(Parsedown $parsedown)
    {
        $this->parsedown = $parsedown;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('markdownify', [$this->parsedown, 'parse']),
        ];
    }
}
