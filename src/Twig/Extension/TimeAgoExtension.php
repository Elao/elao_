<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class TimeAgoExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('time_ago', [$this, 'timeAgo']),
        ];
    }

    public function timeAgo(\DateTimeImmutable $dateTime): string
    {
        $now = new \DateTimeImmutable();
        $interval = $now->diff($dateTime);

        if ($interval->y > 0) {
            return 'il y a ' . $interval->y . ' an' . ($interval->y > 1 ? 's' : '');
        }

        if ($interval->m > 0) {
            return 'il y a ' . $interval->m . ' mois';
        }

        if ($interval->d > 0) {
            return 'il y a ' . $interval->d . ' jour' . ($interval->d > 1 ? 's' : '');
        }

        if ($interval->h > 0) {
            return 'il y a ' . $interval->h . ' heure' . ($interval->h > 1 ? 's' : '');
        }

        if ($interval->i > 0) {
            return 'il y a ' . $interval->i . ' minute' . ($interval->i > 1 ? 's' : '');
        }

        return 'à l\'instant';
    }
}
