<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class AuthorApiController.
 *
 * @package App\Controller
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
class AuthorApiController extends AbstractController
{
    /**
     * Authors to be returned in responses.
     *
     * @var array[]
     */
    protected array $authors = [
        [
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ],
        [
            'id' => 2,
            'first_name' => 'Larry',
            'last_name' => 'Filin',
        ],
        [
            'id' => 3,
            'first_name' => 'Tim',
            'last_name' => 'Stone',
        ],
    ];

    public function list(): JsonResponse
    {
        return new JsonResponse([
            'data' => $this->authors,
            'meta' => [
                'from' => 1,
                'to' => $count = count($this->authors),
                'total' => $count,
                'per_page' => 100,
                'current_page' => 1,
                'last_page' => 1,
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $authors = array_filter(
            $this->authors,
            function (array $a) use ($id) {
                return $a['id'] === $id;
            }
        );

        if (!count($authors)) {
            throw new NotFoundHttpException('Author not found.');
        }

        return new JsonResponse([
            'data' => current($authors),
        ]);
    }
}
