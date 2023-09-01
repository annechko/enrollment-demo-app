<?php

declare(strict_types=1);

namespace App\Controller\Api\Student;

use App\Core\School\Common\RoleEnum;
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
        $this->denyAccessUnlessGranted(RoleEnum::STUDENT_USER->value);

        return new JsonResponse([
            [
                'title' => 'Dashboard',
                'to' => $this->generateUrl(RouteEnum::STUDENT_HOME),
                'type' => 'home',
            ],
            [
                'title' => 'Application',
                'to' => $this->generateUrl(RouteEnum::STUDENT_APPLICATION_LIST),
                'type' => 'application',
            ],
        ]);
    }
}
