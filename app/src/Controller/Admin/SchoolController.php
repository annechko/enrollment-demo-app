<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\School\Common\RoleEnum;
use App\Infrastructure\RouteEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/school')]
class SchoolController extends AbstractController
{
    private const MAX_ITEMS = 20;

    #[Route('', name: RouteEnum::ADMIN_SCHOOL_LIST)]
    public function list(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::ADMIN_USER->value);

        return $this->render('base.html.twig');
    }
}
