<?php

namespace App\Content\Provider;

use App\Model\CaseStudy;
use Content\Behaviour\ContentProviderInterface;

class CaseStudyProvider implements ContentProviderInterface
{
    public function getDirectory(): string
    {
        return 'case-study';
    }

    public function supports(string $className): bool
    {
        return is_a($className, CaseStudy::class, true);
    }
}
