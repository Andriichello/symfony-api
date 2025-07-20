<?php

namespace App\Resource;

use App\Entity\Author;
use App\Entity\Genre;

/**
 * Class GenreResource.
 *
 * @property Genre $entity
 *
 * @method __construct(Genre $entity)
 *
 * @package App\Resource
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class GenreResource extends BaseResource
{
    /**
     * Returns all the attributes extracted from the entity.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return [
            'id' => $this->entity->getId(),
            'name' => $this->entity->getName(),
            'description' => $this->entity->getDescription(),
        ];
    }
}
