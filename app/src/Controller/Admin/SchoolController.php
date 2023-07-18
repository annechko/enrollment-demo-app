<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Domain\School\Common\RoleEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/school')]
class SchoolController extends AbstractController
{
    private const MAX_ITEMS = 20;

    #[Route('', name: 'admin_school_list_show')]
    public function list(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::ADMIN_USER->value);

        return $this->render('base.html.twig');
    }
}
