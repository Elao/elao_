<?php

declare(strict_types=1);

namespace App\Model;

trait MetaTrait
{
    public ?string $metaTitle = null;
    public ?string $metaDescription = null;

    public ?string $ogTitle = null;
    public ?string $ogDescription = null;
    public ?string $ogImage = null;

    public ?string $twitterCardType = null;
    public ?string $twitterTitle = null;
    public ?string $twitterDescription = null;
    public ?string $twitterImage = null;
}
