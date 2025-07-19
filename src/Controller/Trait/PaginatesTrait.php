<?php

namespace App\Controller\Trait;

use App\Entity\Author;
use App\Repository\Interface\HasFiltersInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait PaginatesTrait.
 *
 * @package App\Controller\Trait
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
trait PaginatesTrait
{
    /**
     * @param QueryBuilder $builder
     * @param int $page
     * @param int $perPage
     *
     * @return array{
     *     data: array[]|object[],
     *     meta: array{
     *         from: int,
     *         to: int,
     *         total: int,
     *         per_page: int,
     *         current_page: int,
     *         last_page: int,
     *     }
     * }
     */
    public function paginate(
        QueryBuilder $builder,
        int $page = 1,
        int $perPage = 25
    ): array {
        $builder->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        $paginator = new Paginator($builder);

        $total = $paginator->count();
        $lastPage = (int) ceil($total / $perPage);

        $items = $paginator->getQuery()->getResult();
        $count = count($items);


        return [
            'data' => $items,
            'meta' => [
                'from' => $count ? ($page - 1) * $perPage : null,
                'to' => $count ? ($page - 1) * $perPage + $count : null,
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => $lastPage,
            ],
        ];
    }

    /**
     * @param QueryBuilder $builder
     * @param Request $request
     *
     * @return array{
     *     data: array[]|object[],
     *     meta: array{
     *         from: int,
     *         to: int,
     *         total: int,
     *         per_page: int,
     *         current_page: int,
     *         last_page: int,
     *     }
     * }
     */
    public function paginateBy(
        QueryBuilder $builder,
        Request $request,
    ): array {
        $page = (int) ($request->get('page', 1) ?? 1);
        $perPage = (int) ($request->get('per_page', 25) ?? 25);

        return $this->paginate($builder, $page, $perPage);
    }
}
