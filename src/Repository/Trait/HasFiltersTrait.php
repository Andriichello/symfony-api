<?php

namespace App\Repository\Trait;

use App\Entity\Author;
use App\Repository\Interface\HasFiltersInterface;

/**
 * Trait HasFiltersTrait.
 *
 * @implements HasFiltersInterface
 *
 * @property string[] $allowedFilters
 *
 * @package App\Repository\Trait
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
trait HasFiltersTrait
{
    /**
     * Returns a list of filter names that are allowed.
     *
     * @return array
     */
    public function allowedFilters(): array
    {
        return $this->allowedFilters ?? [];
    }

    /**
     * Returns a list of filter names that are allowed.
     *
     * @param string ...$names
     *
     * @return bool
     */
    public function hasAllowedFilters(string ...$names): bool
    {
        $allowed = $this->allowedFilters();

        foreach ($names as $name) {
            if (!in_array($name, $allowed)) {
                return false;
            }
        }

        return true;
    }
}
