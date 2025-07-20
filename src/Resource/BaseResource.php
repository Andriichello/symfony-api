<?php

namespace App\Resource;

use App\Entity\Author;
use App\Entity\BaseEntity;

/**
 * Class BaseResource.
 *
 * @package App\Resource
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class BaseResource
{
    /**
     * An array of includes to their resources classes.
     *
     * @var array<string, class-string<BaseResource>>
     */
    protected array $includesToResources = [
        //
    ];

    /**
     * BaseResource's constructor.
     *
     * @param BaseEntity $entity
     * @param array $includes
     */
    public function __construct(
        protected BaseEntity $entity,
        protected array $includes = []
    ) {
        //
    }

    /**
     * Sets the includes that should be extracted from the entity.
     *
     * @param array $includes
     *
     * @return static
     */
    public function includes(array $includes): static
    {
        $this->includes = $includes;

        return $this;
    }

    /**
     * Returns all the attributes extracted from the entity.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return (array) $this->entity;
    }

    /**
     * Returns all the includes extracted from the entity.
     *
     * @param array $includes
     *
     * @return array
     */
    public function getIncludes(array $includes): array
    {
        $data = [];

        foreach ($includes as $include) {
            $class = $this->includesToResources[$include] ?? null;

            if (!$class) {
                $data[$include] = 'UNDEFINED INCLUDE!';
                continue;
            }

            $getter = 'get' . ucfirst($include);
            $result = $this->entity->$getter();

            $perform = fn(BaseEntity $entity) => (new $class($entity))->toArray();

            $data[$include] = !is_iterable($result)
                ? $perform($result)
                : array_map($perform, iterator_to_array($result));
        }

        return $data;
    }

    /**
     * Converts the given entity to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            ...$this->getAttributes(),
            ...$this->getIncludes($this->includes),
        ];
    }
}
