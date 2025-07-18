<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class BlogApiController.
 *
 * @package App\Controller
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class BlogApiController extends AbstractController
{
    /**
     * Posts to be returned in responses.
     *
     * @var array[]
     */
    protected array $posts = [
        [
            'id' => 1,
            'title' => 'Post 1',
            'content' => 'Post 1 - Content',
        ],
        [
            'id' => 2,
            'title' => 'Post 2',
            'content' => 'Post 2 - Content',
        ],
        [
            'id' => 3,
            'title' => 'Post 3',
            'content' => 'Post 3 - Content',
        ],
    ];

    #[Route('api/posts', 'posts_list', methods: ['GET', 'HEAD'])]
    public function list(): JsonResponse
    {
        return new JsonResponse([
            'data' => $this->posts,
            'meta' => [
                'from' => 1,
                'to' => $count = count($this->posts),
                'total' => $count,
                'per_page' => 100,
                'current_page' => 1,
                'last_page' => 1,
            ],
        ]);
    }

    #[Route('api/posts/{id}', 'posts_show', ['id' => '[1-9][0-9]*'], methods: ['GET', 'HEAD'])]
    public function show(int $id): JsonResponse
    {
        $posts = array_filter(
            $this->posts,
            function (array $p) use ($id) {
                return $p['id'] === $id;
            }
        );

        if (!count($posts)) {
            throw new NotFoundHttpException('Post not found.');
        }

        return new JsonResponse([
            'data' => current($posts),
        ]);
    }
}
