<?php

namespace App\Resource;

use App\Entity\Author;

/**
 * Class AuthorResource.
 *
 * @property Author $entity
 *
 * @method __construct(Author $entity)
 *
 * @package App\Resource
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class AuthorResource extends BaseResource
{
    /**
     * An array of includes to their resources classes.
     *
     * @var array<string, class-string<BaseResource>>
     */
    protected array $includesToResources = [
        'genres' => GenreResource::class,
    ];

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
            'alias' => $this->entity->getAlias(),
        ];
    }
}
