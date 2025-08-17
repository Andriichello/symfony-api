<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class AuthorController.
 *
 * @package App\Controller
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
final class AuthorController extends AbstractController
{
    #[Route('/web/authors', name: 'web.authors')]
    public function index(AuthorRepository $repo): Response
    {
        $authors = $repo->findAll();

        return $this->render('authors/index.html.twig', [
            'authors' => $authors,
        ]);
    }
}
