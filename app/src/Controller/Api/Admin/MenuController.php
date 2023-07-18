<?php

declare(strict_types=1);

namespace App\Controller\Api\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin')]
class MenuController extends AbstractController
{
    #[Route('/sidebar', name: 'api_admin_sidebar', methods: ['GET'])]
    public function getSidebar(): Response
    {
        return new JsonResponse([
            'navItems' => [
                [
                    'title' => 'admin_school_list',
                    'to' => $this->generateUrl('admin_school_list'),
                    'type' => 'home',
                ],
            ],
        ]);
    }
}
