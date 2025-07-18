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
}
