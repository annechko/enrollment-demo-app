<?php

declare(strict_types=1);

namespace App\Controller\Api\Admin;

use App\Core\School\Common\RoleEnum;
use App\Infrastructure\RouteEnum;
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
        $this->denyAccessUnlessGranted(RoleEnum::ADMIN_USER->value);

        return new JsonResponse([
            'navItems' => [
                [
                    'title' => 'Dashboard',
                    'to' => $this->generateUrl(RouteEnum::ADMIN_HOME),
                    'type' => 'home',
                ],
                [
                    'title' => 'Schools',
                    'to' => $this->generateUrl('admin_school_list_show'),
                    'type' => 'institution',
                ],
            ],
        ]);
    }
}
