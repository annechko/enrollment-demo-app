<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Infrastructure\RouteEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class HomeController extends AbstractController
{
    #[Route('', name: RouteEnum::ADMIN_HOME)]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
}
