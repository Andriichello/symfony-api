<?php

namespace App\Repository\Interface;

use App\Entity\Author;
use App\Enum\FilterFlag;
use App\Enum\FilterOperator;
use App\Query\BaseQueryBuilder;
use App\Repository\BaseRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface FilteringInterface.
 *
 * @mixin BaseRepository
 *
 * @package App\Repository\Interface
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
interface ResolvesFiltersInterface
{
    /**
     * Returns an array of filters in the given query.
     *
     * @param array $query
     *
     * @return array<string, array{
     *     operator: FilterOperator,
     *     flags?: FilterFlag[],
     *     value: mixed,
     * }>
     */
    public function resolveFilters(array $query): array;

    /**
     * Returns an array with operators and flags that are defined in the given filter value.
     *
     * @param mixed $value
     *
     * @return array{
     *     flags: FilterFlag[],
     *     operators: FilterOperator[],
     *     value: mixed,
     * }
     * @throws
     */
    public function resolveFilterFlagsAndOperators(mixed $value): array;
}
