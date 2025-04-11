<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class FormatKExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_k', [$this, 'formatK']),
        ];
    }

    public function formatK(int $number): string
    {
        if ($number >= 1000) {
            return round($number / 1000) . 'K';
        }

        return (string) $number;
    }
}
