<?php

namespace App\Resource;

use App\Entity\User;

/**
 * Class UserResource.
 *
 * @property User $entity
 *
 * @method __construct(User $entity)
 *
 * @package App\Resource
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class UserResource extends BaseResource
{
    /**
     * An array of includes to their resources classes.
     *
     * @var array<string, class-string<BaseResource>>
     */
    protected array $includesToResources = [
        // nothing here, yet...
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
            'email' => $this->entity->getEmail(),
        ];
    }
}
