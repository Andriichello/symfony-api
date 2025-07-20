<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\BaseEntity;
use App\Enum\FilterFlag;
use App\Enum\FilterOperator;
use App\Query\BaseQueryBuilder;
use App\Repository\Interface\HasFiltersInterface;
use App\Repository\Interface\HasIncludesInterface;
use App\Repository\Interface\ResolvesFiltersInterface;
use App\Repository\Interface\ResolvesIncludesInterface;
use App\Repository\Trait\HasFiltersTrait;
use App\Repository\Trait\HasIncludesTrait;
use App\Repository\Trait\ResolvesFiltersTrait;
use App\Repository\Trait\ResolvesIncludesTrait;
use App\Resource\BaseResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AuthorRepository.
 *
 * @extends ServiceEntityRepository<BaseEntity>
 *
 * @package App\Repository
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
abstract class BaseRepository extends ServiceEntityRepository implements
    HasFiltersInterface,
    ResolvesFiltersInterface,
    HasIncludesInterface,
    ResolvesIncludesInterface
{
    use HasFiltersTrait;
    use ResolvesFiltersTrait;
    use HasIncludesTrait;
    use ResolvesIncludesTrait;

    /**
     * Entity class to be used.
     *
     * @var class-string<BaseEntity>
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
     * Include names that are allowed.
     *
     * @var string[]
     */
    protected array $allowedIncludes = [
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
     * @return class-string<BaseEntity>
     */
    public function entityClass(): string
    {
        return $this->entityClass;
    }

    /**
     * Returns an entity class.
     *
     * @return class-string<BaseResource>
     */
    public function resourceClass(): string
    {
        return $this->resourceClass;
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
     * Applies filters onto a given query builder.
     *
     * @param BaseQueryBuilder $builder
     * @param string $alias
     * @param array $filters
     *
     * @return BaseQueryBuilder
     */
    public function applyFilters(BaseQueryBuilder $builder, string $alias, array $filters): BaseQueryBuilder
    {
        foreach ($this->resolveFilters($filters) as $filter) {
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

    /**
     * Applies includes onto a given query builder.
     *
     * @param BaseQueryBuilder $builder
     * @param string $alias
     * @param array $includes
     *
     * @return BaseQueryBuilder
     */
    public function applyIncludes(BaseQueryBuilder $builder, string $alias, array $includes): BaseQueryBuilder
    {
        foreach ($this->resolveIncludes($includes) as $include) {
            $builder->leftJoin("$alias.$include", $include);
        }

        return $builder;
    }
}
