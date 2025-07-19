<?php

namespace App\Controller;

use App\Entity\Author;
use App\Query\AuthorQueryBuilder;
use App\Repository\AuthorRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AuthorApiController.
 *
 * @package App\Controller
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class AuthorApiController extends AbstractController
{
    /**
     * AuthorApiController's constructor.
     *
     * @param AuthorRepository $repo
     */
    public function __construct(
        private readonly AuthorRepository $repo
    ) {
        //
    }

    /**
     * Returns a list of author records.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $filter = $request->get('filter');

        if (isset($filter) && is_array($filter)) {
            if (isset($search) && strlen($search) > 0) {
                $message = 'Cannot use both `filter` and `search` parameters' .
                    ' at the same time.';

                return new JsonResponse(['message' => $message], 400);
            }
        }

        $builder = $this->repo->createQueryBuilder($alias = 'a');
        $builder = $this->repo->applyRequestFilters($builder, $alias, $request);

        if (isset($search) && strlen($search) > 0) {
            /** @var AuthorQueryBuilder $builder */
            $builder = $this->repo->search($alias, $builder, $search);
        }

        $authors = $builder->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();

        $data = array_map(
            function (Author $author) {
                return [
                    'id' => $author->getId(),
                    'name' => $author->getName(),
                    'alias' => $author->getAlias(),
                ];
            },
            $authors
        );

        return new JsonResponse([
            'data' => $data,
            'meta' => [
                'from' => (int) !empty($authors),
                'to' => $count = count($authors),
                'total' => $count,
                'per_page' => 'all',
                'current_page' => 1,
                'last_page' => 1,
            ],
            'message' => 'OK',
        ]);
    }

    /**
     * Returns an author record by ID.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $author = $this->repo->find($id);

        if (empty($author)) {
            return new JsonResponse(['message' => 'Author not found.'], 404);
        }

        return new JsonResponse([
            'data' => [
                'id' => $author->getId(),
                'name' => $author->getName(),
                'alias' => $author->getAlias(),
            ],
            'message' => 'OK',
        ]);
    }
}
