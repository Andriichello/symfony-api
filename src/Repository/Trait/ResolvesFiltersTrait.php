<?php

namespace App\Repository\Trait;

use App\Entity\Author;
use App\Enum\FilterFlag;
use App\Enum\FilterOperator;
use App\Repository\Interface\HasFiltersInterface;
use App\Repository\Interface\ResolvesFiltersInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait ResolvesFiltersTrait.
 *
 * @mixin HasFiltersTrait
 *
 * @package App\Repository\Trait
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
trait ResolvesFiltersTrait
{
    /**
     * Returns an array of filters in the given query.
     *
     * @param array $query
     *
     * @return array<int, array{
     *     name: string,
     *     operator: FilterOperator,
     *     flags?: FilterFlag[],
     *     value: mixed,
     * }>
     */
    public function resolveFilters(array $query): array
    {
        $filters = [];

        foreach ($query as $name => $value) {
            if (!$this->hasAllowedFilters($name)) {
                throw new InvalidArgumentException(
                    "Filter '{$name}' is not allowed."
                );
            }

            $resolved = $this->resolveFilterFlagsAndOperators($value);

            if (count($resolved['operators']) > 1) {
                throw new InvalidArgumentException(
                    "Filter '{$name}' must have only one operator."
                );
            }

            foreach ($resolved['flags'] as $flag) {
                $same = array_filter(
                    $resolved['flags'],
                    fn(FilterFlag $f) => $f === $flag
                );

                if (count($same) > 1) {
                    throw new InvalidArgumentException(
                        "Filter '{$name}' must not have duplicate flags."
                    );
                }
            }

            $filter = [
                'name' => $name,
                'operator' => $resolved['operators'][0] ?? FilterOperator::EQ,
                'value' => $resolved['value'],
            ];

            if (!empty($resolved['flags'])) {
                $filter['flags'] = $resolved['flags'];
            }

            $filters[] = $filter;
        }

        return $filters;
    }

    /**
     * Returns an array with operator and flags that are defined in the given filter value.
     *
     * @param mixed $value
     *
     * @return array{
     *     flags: FilterFlag[],
     *     operators: FilterOperator[],
     *     value: mixed,
     * }
     * @throws InvalidArgumentException
     */
    public function resolveFilterFlagsAndOperators(mixed $value): array
    {
        if (!is_array($value) || empty($value)) {
            return ['flags' => [], 'operators' => [], 'value' => $value];
        }

        $key = array_key_first($value);

        $flag = FilterFlag::tryFrom($key);
        $operator = FilterOperator::tryFrom($key);

        if ($flag === null && $operator === null) {
            throw new InvalidArgumentException(
                "The '$key' is neither a filter flag or an operator. "
            );
        }

        $result = $this->resolveFilterFlagsAndOperators($value[$key]);

        $flags = [$flag, ...($result['flags'] ?? [])];
        $flags = array_values(array_filter($flags));

        $operators = [$operator, ...($result['operators'] ?? [])];
        $operators = array_values(array_filter($operators));

        $value = $result['value'];

        return compact('flags', 'operators', 'value');
    }
}
