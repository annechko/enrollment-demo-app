<?php

declare(strict_types=1);

namespace App\Controller\Student;

use App\Domain\School\Common\RoleEnum;
use App\Domain\School\UseCase\Member;
use App\Infrastructure\RouteEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student')]
class StudentController extends AbstractController
{
    #[Route('/', name: RouteEnum::STUDENT_HOME)]
    public function home(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::STUDENT_USER->value);

        return $this->render('base.html.twig');
    }
}
