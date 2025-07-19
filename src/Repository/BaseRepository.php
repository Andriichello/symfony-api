<?php

namespace App\Repository;

use App\Entity\Author;
use App\Enum\FilterFlag;
use App\Enum\FilterOperator;
use App\Query\BaseQueryBuilder;
use App\Repository\Interface\HasFiltersInterface;
use App\Repository\Interface\ResolvesFiltersInterface;
use App\Repository\Trait\HasFiltersTrait;
use App\Repository\Trait\ResolvesFiltersTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AuthorRepository.
 *
 * @extends ServiceEntityRepository<Entity>
 *
 * @package App\Repository
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
abstract class BaseRepository extends ServiceEntityRepository implements
    HasFiltersInterface,
    ResolvesFiltersInterface
{
    use HasFiltersTrait;
    use ResolvesFiltersTrait;

    /**
     * Entity class to be used.
     *
     * @var class-string<Entity>
     */
    protected string $entityClass;

    /**
     * Query builder class to be used.
     *
     * @var class-string<BaseQueryBuilder>
     */
    protected string $queryBuilderClass;

    /**
     * Filter names that are allowed.
     *
     * @var string[]
     */
    protected array $allowedFilters = [
        //
    ];

    /**
     * BaseRepository's constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->entityClass());
    }

    /**
     * Returns an entity class.
     *
     * @return class-string<Entity>
     */
    public function entityClass(): string
    {
        return $this->entityClass;
    }

    /**
     * Returns a query builder class string.
     *
     * @return class-string<QueryBuilder>
     */
    public function queryBuilderClass(): string
    {
        return $this->queryBuilderClass ?? QueryBuilder::class;
    }

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @param string $alias
     * @param string|null $indexBy
     *
     * @return BaseQueryBuilder
     */
    public function createQueryBuilder(string $alias, ?string $indexBy = null): BaseQueryBuilder
    {
        $class = $this->queryBuilderClass();

        /** @var BaseQueryBuilder $builder */
        $builder = new $class($this->getEntityManager());

        $builder->select($alias)
            ->from($this->entityClass(), $alias);

        if ($indexBy !== null) {
            $builder->indexBy($alias, $indexBy);
        }

        return $builder;
    }

    /**
     * Applies request filters onto a given query builder.
     *
     * @param BaseQueryBuilder $builder
     * @param string $alias
     * @param Request $request
     *
     * @return BaseQueryBuilder
     */
    public function applyRequestFilters(BaseQueryBuilder $builder, string $alias, Request $request): BaseQueryBuilder
    {
        $filters = $this->resolveFilters((array) $request->get('filter'));

        foreach ($filters as $filter) {
            $column = "$alias.{$filter['name']}";
            $operator = $filter['operator'];
            $flags = $filter['flags'] ?? [];

            $not = in_array(FilterFlag::NOT, $flags);
            $ignoreCase = in_array(FilterFlag::IC, $flags);
            $split = in_array(FilterFlag::SP, $flags);

            switch ($operator) {
                case FilterOperator::LIKE:
                    $split
                        ? $builder->whereColumnLikeByWords($column, $filter['value'], $ignoreCase, not: $not)
                        : $builder->whereColumnLike($column, $filter['value'], $ignoreCase, not: $not);
                    break;

                default:
                    $builder->whereColumn(
                        $column,
                        $filter['value'],
                        $operator,
                        $ignoreCase,
                        not: $not
                    );
            }
        }

        return $builder;
    }
}
