<?php

namespace App\Repository\Trait;

use App\Entity\Author;
use InvalidArgumentException;

/**
 * Trait ResolvesIncludesTrait.
 *
 * @mixin HasIncludesTrait
 *
 * @package App\Repository\Trait
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
trait ResolvesIncludesTrait
{
    /**
     * Returns an array of filters in the given query.
     *
     * @param string[] $names
     *
     * @return string[]
     */
    public function resolveIncludes(array $names): array
    {
        $names =  array_values(array_unique($names));

        foreach ($names as $name) {
            if (!$this->hasAllowedIncludes($name)) {
                throw new InvalidArgumentException(
                    "Include '{$name}' is not allowed."
                );
            }
        }

        return $names;
    }
}
