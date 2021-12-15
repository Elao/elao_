<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class StdClassDenormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const PROCESSING = 'std_class_processing';
    private PropertyAccessorInterface $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): mixed
    {
        $context[self::PROCESSING] = true;

        /** @var \stdClass $object */
        $object = $this->denormalizer->denormalize($data, $type, $format, $context);

        \assert(\is_array($data));

        foreach ($data as $key => $value) {
            if (!$this->propertyAccessor->isReadable($object, $key)) {
                $object->$key = $value;
            }
        }

        return $object;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        if (isset($context[self::PROCESSING])) {
            return false;
        }

        return is_a($type, \stdClass::class, true);
    }
}
