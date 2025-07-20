<?php

namespace App\Repository\Interface;

use App\Entity\Author;
use App\Enum\FilterOperator;
use App\Query\BaseQueryBuilder;
use App\Repository\BaseRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface HasIncludesInterface.
 *
 * @mixin BaseRepository
 *
 * @package App\Repository\Interface
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
interface HasIncludesInterface
{
    /**
     * Returns a list of include names that are allowed.
     *
     * @return array
     */
    public function allowedIncludes(): array;

    /**
     * Checks if all the given include names are allowed
     *
     * @param string ...$names
     *
     * @return bool
     */
    public function hasAllowedIncludes(string ...$names): bool;
}
