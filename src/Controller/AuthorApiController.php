<?php

namespace App\Controller;

use App\Controller\Trait\PaginatesTrait;
use App\Entity\Author;
use App\Query\AuthorQueryBuilder;
use App\Repository\AuthorRepository;
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
    use PaginatesTrait;

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

        $builder->orderBy('a.id', 'ASC');
        $paginated = $this->paginateBy($builder, $request);

        return new JsonResponse([
            'data' => array_map(
                fn(Author $author) => $this->toArray($author),
                $paginated['data']
            ),
            'meta' => $paginated['meta'],
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
            'data' => $this->toArray($author),
            'message' => 'OK',
        ]);
    }

    /**
     * Converts an author record to an array that can be returned in the response.
     *
     * @param Author $author
     *
     * @return array
     */
    private function toArray(Author $author): array
    {
        return [
            'id' => $author->getId(),
            'name' => $author->getName(),
            'alias' => $author->getAlias(),
        ];
    }
}
