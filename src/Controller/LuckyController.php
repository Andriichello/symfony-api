<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class LuckyController extends AbstractController
{
    #[Route('lucky/number', 'lucky_number', methods: ['GET'])]
    #[Template('lucky/number.html.twig')]
    public function number(): array
    {
        return ['number' => rand(0, 100)];
    }
}
