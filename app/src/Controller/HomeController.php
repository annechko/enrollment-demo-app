<?php

declare(strict_types=1);

namespace App\Controller;

use App\Infrastructure\RouteEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('', name: RouteEnum::HOME)]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
}
