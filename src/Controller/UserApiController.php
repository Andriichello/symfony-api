<?php

namespace App\Controller;

use App\Controller\Trait\PaginatesTrait;
use App\Entity\Author;
use App\Entity\Genre;
use App\Entity\User;
use App\Query\UserQueryBuilder;
use App\Repository\UserRepository;
use App\Resource\UserResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserApiController.
 *
 * @package App\Controller
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class UserApiController extends AbstractController
{
    use PaginatesTrait;

    /**
     * UserApiController's constructor.
     *
     * @param UserRepository $repo
     */
    public function __construct(
        private readonly UserRepository $repo
    ) {
        //
    }

    /**
     * Returns a list of user records.
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
            /** @var UserQueryBuilder $builder */
            $builder = $this->repo->search($alias, $builder, $search);
        }

        $paginated = $this->paginateByRequest($builder, $request);

        return new JsonResponse([
            'data' => array_map(
                fn(User $user) => $this->toArray($user, $includes),
                $paginated['data']
            ),
            'meta' => $paginated['meta'],
            'message' => 'OK',
        ]);
    }

    /**
     * Returns a user record by ID.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->repo->find($id);

        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found.'], 404);
        }

        return new JsonResponse([
            'data' => $this->toArray($user),
            'message' => 'OK',
        ]);
    }

    /**
     * Converts a user record to an array that can be returned in the response.
     *
     * @param User $user
     * @param array $includes
     *
     * @return array
     */
    private function toArray(User $user, array $includes = []): array
    {
        return (new UserResource($user, $includes))->toArray();
    }
}
