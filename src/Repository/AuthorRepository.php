<?php

namespace App\Repository;

use App\Entity\Author;
use App\Query\AuthorQueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Class AuthorRepository.
 *
 * @extends ServiceEntityRepository<Author>
 *
 * @method AuthorQueryBuilder createQueryBuilder(string $alias, ?string $indexBy = null)
 *
 * @package App\Repository
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class AuthorRepository extends BaseRepository
{
    protected string $entityClass = Author::class;

    protected string $queryBuilderClass = AuthorQueryBuilder::class;

    protected array $allowedFilters = [
        'id',
        'name',
        'alias',
    ];

    /**
     * Searches authors by the given search query.
     *
     * @param string $alias
     * @param AuthorQueryBuilder $builder
     * @param string $query
     *
     * @return AuthorQueryBuilder
     */
    public function search(string $alias, AuthorQueryBuilder $builder, string $query): AuthorQueryBuilder
    {
        return $builder->whereColumnLikeByWords("$alias.name", $query, true)
            ->whereColumnLikeByWords("$alias.alias", $query, true, and: false);
    }
}
