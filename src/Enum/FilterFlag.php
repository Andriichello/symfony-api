<?php

namespace App\Enum;

use App\Repository\Interface\HasFiltersInterface;
use BackedEnum;

/**
 * Enum FilterFlag.
 *
 * @package App\Enum
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
enum FilterFlag: string
{
    /** Not (Flag) */
    case NOT = 'not';

    /** Ignore Case (Flag) */
    case IC = 'ic';

    /** Split into Words (Flag) */
    case SP = 'sp';
}
