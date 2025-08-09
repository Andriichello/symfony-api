<?php

namespace App\Repository;

use App\Entity\User;
use App\Query\UserQueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Class UserRepository.
 *
 * @extends ServiceEntityRepository<User>
 *
 * @method UserQueryBuilder createQueryBuilder(string $alias, ?string $indexBy = null)
 *
 * @package App\Repository
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class UserRepository extends BaseRepository
{
    protected string $entityClass = User::class;

    protected string $queryBuilderClass = UserQueryBuilder::class;

    protected array $allowedFilters = [
        'id',
        'name',
        'email',
        'roles',
    ];

    /**
     * Searches genres by the given search query.
     *
     * @param string $alias
     * @param UserQueryBuilder $builder
     * @param string $query
     *
     * @return UserQueryBuilder
     */
    public function search(string $alias, UserQueryBuilder $builder, string $query): UserQueryBuilder
    {
        return $builder->whereColumnLikeByWords("$alias.name", $query, true);
    }
}
