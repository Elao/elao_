<?php

declare(strict_types=1);

namespace App\Model;

use Elao\Enum\AutoDiscoveredValuesTrait;
use Elao\Enum\ReadableEnum;

/**
 * @extends ReadableEnum<string>
 *
 * @method static JobContractType CDI()
 * @method static JobContractType CDD()
 * @method static JobContractType INTERNSHIP()
 * @method static JobContractType WORK_STUDY()
 */
class JobContractType extends ReadableEnum
{
    /** @use AutoDiscoveredValuesTrait<string> */
    use AutoDiscoveredValuesTrait;

    public const CDI = 'CDI';
    public const CDD = 'CDD';
    public const INTERNSHIP = 'INTERNSHIP';
    public const WORK_STUDY = 'WORK-STUDY';

    /**
     * @return array<string, string>
     */
    public static function readables(): array
    {
        return [
            self::CDI => 'CDI',
            self::CDD => 'CDD',
            self::INTERNSHIP => 'Stage',
            self::WORK_STUDY => 'Alternance',
        ];
    }
}
