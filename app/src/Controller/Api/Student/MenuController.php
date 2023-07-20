<?php

declare(strict_types=1);

namespace App\Controller\Api\Student;

use App\Infrastructure\RouteEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/student')]
class MenuController extends AbstractController
{
    #[Route('/sidebar', name: 'api_student_sidebar', methods: ['GET'])]
    public function getSidebar(): Response
    {
        return new JsonResponse([
            'navItems' => [
                [
                    'title' => 'Dashboard',
                    'to' => $this->generateUrl(RouteEnum::STUDENT_HOME),
                    'type' => 'home',
                ],
            ],
        ]);
    }
}
