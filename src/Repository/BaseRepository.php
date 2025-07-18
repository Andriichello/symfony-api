<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AuthorRepository.
 *
 * @extends ServiceEntityRepository<Entity>
 *
 * @package App\Repository
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
abstract class BaseRepository extends ServiceEntityRepository
{
    /**
     * @var class-string<Entity>
     */
    protected string $entityClass;

    /**
     * @var class-string<QueryBuilder>
     */
    protected string $queryBuilderClass;

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
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $alias, ?string $indexBy = null): QueryBuilder
    {
        $class = $this->queryBuilderClass();

        $builder = new $class($this->getEntityManager());

        $builder->select($alias)
            ->from($this->entityClass(), $alias);

        if ($indexBy !== null) {
            $builder->indexBy($alias, $indexBy);
        }

        return $builder;
    }
}
