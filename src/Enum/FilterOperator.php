<?php

namespace App\Enum;

/**
 * Enum FilterOperator.
 *
 * @package App\Enum
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
enum FilterOperator: string
{
    /** Equal */
    case EQ = 'eq';

    /** Greater Than */
    case GT = 'gt';

    /** Less Than */
    case LT = 'lt';

    /** Greater Than or Equal */
    case GTE = 'gte';

    /** Less Than or Equal */
    case LTE = 'lte';

    /** Like */
    case LIKE = 'like';

    /** Null */
    case NULL = 'null';
}
