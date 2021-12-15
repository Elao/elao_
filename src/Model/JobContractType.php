<?php

declare(strict_types=1);

namespace App\Model;

use Elao\Enum\Attribute\EnumCase;
use Elao\Enum\ReadableEnumInterface;
use Elao\Enum\ReadableEnumTrait;

enum JobContractType: string implements ReadableEnumInterface
{
    use ReadableEnumTrait;

    #[EnumCase('CDI')]
    case CDI = 'CDI';

    #[EnumCase('CDD')]
    case CDD = 'CDD';

    #[EnumCase('Stage')]
    case Internship = 'INTERNSHIP';

    #[EnumCase('Alternance')]
    case WorkStudy = 'WORK-STUDY';

}
