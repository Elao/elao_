<?php

declare(strict_types=1);

namespace App\Stenope\Serializer;

use App\Model\Member;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class MemberNormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if ($data instanceof Member) {
            return $data;
        }

        $fr = $data['fr'];
        $social = $data['social'] ?? [];

        $integrationDate = null;
        if (isset($data['integrationDate'])) {
            $integrationDate = \DateTime::createFromFormat('d/m/Y', $data['integrationDate']);
            if ($integrationDate === false) {
                $integrationDate = null;
            }
        }

        return new Member(
            $data['slug'],
            $fr['name'] ?? $fr['display_name'],
            $data['active'] ?? false,
            $fr['pseudo'] ?? null,
            $fr['short_bio'],
            $fr['long_bio'],
            $fr['job_title'] ?? null,
            $social['website'] ?? null,
            $social['twitter'] ?? null,
            $social['github'] ?? null,
            $social['email'] ?? null,
            $social['avatar'] ?? null,
            $social['linkedIn'] ?? null,
            $data['certifications'] ?? [],
            $integrationDate,
            $data['emojis'] ?? [],
            $data['🚲'] ?? false,
            $data['gender'] ?? null
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_a($type, Member::class, true);
    }
}
