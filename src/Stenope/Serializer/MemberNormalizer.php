<?php

declare(strict_types=1);

namespace App\Stenope\Serializer;

use App\Model\Member;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class MemberNormalizer implements DenormalizerInterface
{
    private ObjectNormalizer $objectNormalizer;

    public function __construct(ObjectNormalizer $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if ($data instanceof Member) {
            // Don't try to denormalize the Member again if already instantiated
            // (if injected in another content through a processor)
            return $data;
        }

        if (isset($data['integrationDate'])) {
            $data['integrationDate'] = \DateTime::createFromFormat('d/m/Y', $data['integrationDate']);
        }

        return $this->objectNormalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_a($type, Member::class, true);
    }
}
