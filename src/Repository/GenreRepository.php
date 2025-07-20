<?php

namespace App\Repository;

use App\Entity\Genre;
use App\Query\GenreQueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Class GenreRepository.
 *
 * @extends ServiceEntityRepository<Genre>
 *
 * @method GenreQueryBuilder createQueryBuilder(string $alias, ?string $indexBy = null)
 *
 * @package App\Repository
 * @author Andrii Prykhodko <andriichello@gmail.com>
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
