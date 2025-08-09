<?php

namespace App\Controller;

use App\Controller\Trait\PaginatesTrait;
use App\Entity\Author;
use App\Entity\Genre;
use App\Query\GenreQueryBuilder;
use App\Repository\GenreRepository;
use App\Resource\GenreResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GenreApiController.
 *
 * @package App\Controller
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class GenreApiController extends AbstractController
{
    use PaginatesTrait;

    /**
     * GenreApiController's constructor.
     *
     * @param GenreRepository $repo
     */
    public function __construct(
        private readonly GenreRepository $repo
    ) {
        //
    }

    /**
     * Returns a list of genre records.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $filters = (array) $request->get('filter');
        $includes = (array) $request->get('include');

        if (!empty($filters)) {
            if (isset($search) && strlen($search) > 0) {
                $message = 'Cannot use both `filter` and `search` parameters' .
                    ' at the same time.';

                return new JsonResponse(['message' => $message], 400);
            }
        }

        $builder = $this->repo->createQueryBuilder($alias = 'g');
        $builder = $this->repo->applyIncludes($builder, $alias, $includes);
        $builder = $this->repo->applyFilters($builder, $alias, $filters);

        if (isset($search) && strlen($search) > 0) {
            /** @var GenreQueryBuilder $builder */
            $builder = $this->repo->search($alias, $builder, $search);
        }

        $paginated = $this->paginateByRequest($builder, $request);

        return new JsonResponse([
            'data' => array_map(
                fn(Genre $genre) => $this->toArray($genre, $includes),
                $paginated['data']
            ),
            'meta' => $paginated['meta'],
            'message' => 'OK',
        ]);
    }

    /**
     * Returns a genre record by ID.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $author = $this->repo->find($id);

        if (empty($author)) {
            return new JsonResponse(['message' => 'Genre not found.'], 404);
        }

        return new JsonResponse([
            'data' => $this->toArray($author),
            'message' => 'OK',
        ]);
    }

    /**
     * Converts a genre record to an array that can be returned in the response.
     *
     * @param Genre $genre
     * @param array $includes
     *
     * @return array
     */
    private function toArray(Genre $genre, array $includes = []): array
    {
        return (new GenreResource($genre, $includes))->toArray();
    }
}
