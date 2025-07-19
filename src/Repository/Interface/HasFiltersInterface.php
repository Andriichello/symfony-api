<?php

namespace App\Repository\Interface;

use App\Entity\Author;
use App\Enum\FilterOperator;
use App\Query\BaseQueryBuilder;
use App\Repository\BaseRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface HasFiltersInterface.
 *
 * @mixin BaseRepository
 *
 * @package App\Repository\Interface
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
interface HasFiltersInterface
{
    /**
     * Returns a list of filter names that are allowed.
     *
     * @return array
     */
    public function allowedFilters(): array;

    /**
     * Checks if all the given filter names are allowed
     *
     * @param string ...$names
     *
     * @return bool
     */
    public function hasAllowedFilters(string ...$names): bool;
}
