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
     * @param string $name
     *
     * @return QueryBuilder
     */
    private function whereName(string $name): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.name = :val')
            ->setParameter('val', $name)
            ->orderBy('a.id', 'ASC');
    }

    /**
     * Searches authors by name, returns all found records.
     *
     * @param string $name
     *
     * @return Author[]
     */
    public function findByName(string $name): array
    {
        return $this->whereName($name)
            ->getQuery()
            ->getResult();
    }

    /**
     * Search authors by name, returns the first found record.
     *
     * @param string $name
     *
     * @return Author|null
     */
    public function findOneByName(string $name): ?Author
    {
        return $this->whereName($name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Adds a where statement for the `alias` column.
     *
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    private function whereAlias(?string $alias): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.alias = :val')
            ->setParameter('val', $alias)
            ->orderBy('a.id', 'ASC');
    }

    /**
     * Searches authors by alias, returns all found records.
     *
     * @param string|null $alias
     *
     * @return Author[]
     */
    public function findByAlias(?string $alias): array
    {
        return $this->whereAlias($alias)
            ->getQuery()
            ->getResult();
    }

    /**
     * Search authors by alias, returns the first found record.
     *
     * @param string|null $alias
     *
     * @return Author|null
     */
    public function findOneByAlias(?string $alias): ?Author
    {
        return $this->whereAlias($alias)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
