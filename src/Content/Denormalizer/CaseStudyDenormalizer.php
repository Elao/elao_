<?php

namespace App\Content\Denormalizer;

use App\Model\CaseStudy;
use Content\Behaviour\ContentDenormalizerInterface;

class CaseStudyDenormalizer implements ContentDenormalizerInterface
{
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return new CaseStudy(
            $data['title'],
            $data['description'] ?? null,
            $data['slug'],
            $data['content'],
            $data['date'],
            $data['lastModified']
        );
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return is_a($type, CaseStudy::class, true);
    }
}
