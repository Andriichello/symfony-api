<?php

namespace App\Controller;

use App\Entity\Author;
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
        $authors = empty($name = $request->query->get('name'))
            ? $this->repo->findAll()
            : $this->repo->searchByName($name);

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
