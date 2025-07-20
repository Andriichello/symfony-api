<?php

namespace App\Repository\Trait;

use App\Entity\Author;
use App\Repository\Interface\HasFiltersInterface;

/**
 * Trait HasFiltersTrait.
 *
 * @implements HasFiltersInterface
 *
 * @property string[] $allowedIncludes
 *
 * @package App\Repository\Trait
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
trait HasIncludesTrait
{
    /**
     * Returns a list of include names that are allowed.
     *
     * @return string[]
     */
    public function allowedIncludes(): array
    {
        return $this->allowedIncludes ?? [];
    }

    /**
     * Returns a list of include names that are allowed.
     *
     * @param string ...$names
     *
     * @return bool
     */
    public function hasAllowedIncludes(string ...$names): bool
    {
        $allowed = $this->allowedIncludes();

        foreach ($names as $name) {
            if (!in_array($name, $allowed)) {
                return false;
            }
        }

        return true;
    }
}
