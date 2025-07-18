<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AuthorRepository.
 *
 * @extends ServiceEntityRepository<Author>
 *
 * @package App\Repository
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class AuthorRepository extends ServiceEntityRepository
{
    /**
     * AuthorRepository's constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * Adds a where statement for the `name` column.
     *
     * @param QueryBuilder $builder
     * @param string $name
     *
     * @return QueryBuilder
     */
    private function whereName(QueryBuilder $builder, string $name): QueryBuilder
    {
        return $builder->andWhere('a.name = :val_name')
            ->setParameter('val_name', $name);
    }

    /**
     * Find authors by name, returns all found records.
     *
     * @param string $name
     *
     * @return Author[]
     */
    public function findByName(string $name): array
    {
        $builder = $this->createQueryBuilder('a');

        return $this->whereName($builder, $name)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find author by name, returns the first found record.
     *
     * @param string $name
     *
     * @return Author|null
     */
    public function findOneByName(string $name): ?Author
    {
        $builder = $this->createQueryBuilder('a');

        return $this->whereName($builder, $name)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Adds a where (like) statement for the `name` column.
     * Splits a given name value into words and searches for authors,
     * whose `name` includes at least one of those words.
     *
     * @param QueryBuilder $builder
     * @param string $name
     *
     * @return QueryBuilder
     */
    private function whereNameLike(QueryBuilder $builder, string $name): QueryBuilder
    {
        $name = strtolower($name);

        $words = preg_split('/\s+/', trim($name));
        $conditions = [];

        foreach ($words as $i => $word) {
            $param = "val_name_{$i}";
            $conditions[] = "LOWER(a.name) LIKE :{$param}";
            $builder->setParameter($param, "%{$word}%");
        }

        return $builder->andWhere(implode(' OR ', $conditions));
    }

    /**
     * Searches authors by name, returns all found records.
     * Splits a given name value into words and searches for authors,
     * whose `name` includes at least one of those words.
     *
     * @param string $name
     *
     * @return Author[]
     */
    public function searchByName(string $name): array
    {
        $builder = $this->createQueryBuilder('a');

        return $this->whereNameLike($builder, $name)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Adds a where statement for the `alias` column.
     *
     * @param QueryBuilder $builder
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    private function whereAlias(QueryBuilder $builder, ?string $alias): QueryBuilder
    {
        return $builder->andWhere('a.alias = :val_alias')
            ->setParameter('val_alias', $alias);
    }

    /**
     * Find authors by alias, returns all found records.
     *
     * @param string|null $alias
     *
     * @return Author[]
     */
    public function findByAlias(?string $alias): array
    {
        $builder = $this->createQueryBuilder('a');

        return $this->whereAlias($builder, $alias)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find author by alias, returns the first found record.
     *
     * @param string|null $alias
     *
     * @return Author|null
     */
    public function findOneByAlias(?string $alias): ?Author
    {
        $builder = $this->createQueryBuilder('a');

        return $this->whereAlias($builder, $alias)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Adds a where (like) statement for the `alias` column.
     *
     * @param QueryBuilder $builder
     * @param string $alias
     *
     * @return QueryBuilder
     */
    private function whereAliasLike(QueryBuilder $builder, string $alias): QueryBuilder
    {
        $alias = strtolower($alias);

        $words = preg_split('/\s+/', trim($alias));
        $conditions = [];

        foreach ($words as $i => $word) {
            $param = "val_alias_{$i}";
            $conditions[] = "LOWER(a.alias) LIKE :{$param}";
            $builder->setParameter($param, "%{$word}%");
        }

        return $builder->andWhere(implode(' OR ', $conditions));
    }

    /**
     * Searches authors by alias, returns all found records.
     * Splits a given alias value into words and searches for authors,
     * whose `alias` includes at least one of those words.
     *
     * @param string $alias
     *
     * @return Author[]
     */
    public function searchByAlias(string $alias): array
    {
        $builder = $this->createQueryBuilder('a');

        return $this->whereNameLike($builder, $alias)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
