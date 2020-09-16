<?php

namespace App\Content\Serializer;

use App\Model\Member;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class MemberNormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if ($data instanceof Member) {
            return $data;
        }

        //dump('couocu', $data);
        $fr = $data['fr'];
        $social = $data['social'] ?? [];

        return new Member(
            $data['slug'],
            $fr['name'] ?? $fr['display_name'],
            $fr['short_bio'],
            $fr['long_bio'],
            $fr['job_title'] ?? null,
            $social['website'] ?? null,
            $social['twitter'] ?? null,
            $social['github'] ?? null,
            $social['email'] ?? null,
            $social['avatar'] ?? null
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_a($type, Member::class, true);
    }
}
