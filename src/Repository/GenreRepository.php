<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\Genre;
use App\Query\GenreQueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Genre>
 */
class GenreRepository extends BaseRepository
{
    protected string $entityClass = Genre::class;

    protected string $queryBuilderClass = GenreQueryBuilder::class;

    protected array $allowedFilters = [
        'id',
        'name',
        'description',
    ];

    /**
     * Searches genres by the given search query.
     *
     * @param string $alias
     * @param GenreQueryBuilder $builder
     * @param string $query
     *
     * @return GenreQueryBuilder
     */
    public function search(string $alias, GenreQueryBuilder $builder, string $query): GenreQueryBuilder
    {
        return $builder->whereColumnLikeByWords("$alias.name", $query, true);
    }
}
