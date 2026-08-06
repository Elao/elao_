<?php

declare(strict_types=1);

namespace App\Bridge\Glide\Bundle\Manipulator;

use Intervention\Image\Image;
use League\Glide\Manipulators\Size;

/**
 * Glide hardcodes the "center" anchor when padding an image up to the requested canvas
 * (i.e: `fit=fill` and `fit=fill-max`), leaving no way to align the image on one side.
 *
 * This adds a `fillpos` param to drive that anchor, mirroring the `markpos` naming of
 * the watermark manipulator. Invalid or missing values fall back to Glide's default.
 *
 * @see https://image.intervention.io/v2/api/resizeCanvas for the supported anchors.
 *
 * @property string|null $fillpos
 */
class PositionableFillSize extends Size
{
    private const ANCHORS = [
        'top-left', 'top', 'top-right',
        'left', 'center', 'right',
        'bottom-left', 'bottom', 'bottom-right',
    ];

    /**
     * @param Image $image
     * @param int   $width
     * @param int   $height
     *
     * @return Image
     */
    public function runFillResize($image, $width, $height)
    {
        return $this->runMaxResize($image, $width, $height)
            ->resizeCanvas($width, $height, $this->getFillPosition());
    }

    /**
     * @param int $width
     * @param int $height
     *
     * @return Image
     */
    public function runFillMaxResize(Image $image, $width, $height)
    {
        return $image
            ->resize($width, $height, static fn ($constraint) => $constraint->aspectRatio())
            ->resizeCanvas($width, $height, $this->getFillPosition());
    }

    private function getFillPosition(): string
    {
        $position = $this->fillpos;

        if (null === $position || !\in_array($position, self::ANCHORS, true)) {
            return 'center';
        }

        return $position;
    }
}
