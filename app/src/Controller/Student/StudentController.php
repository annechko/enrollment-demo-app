<?php

declare(strict_types=1);

namespace App\Controller\Student;

use App\Core\School\Common\RoleEnum;
use App\Core\Student\Repository\StudentRepository;
use App\Core\Student\Service\StudentEmailVerifier;
use App\Infrastructure\RouteEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/student')]
class StudentController extends AbstractController
{
    #[Route('/', name: RouteEnum::STUDENT_HOME)]
    public function home(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::STUDENT_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/applications/add', name: RouteEnum::STUDENT_APPLICATION)]
    public function applicationAdd(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::STUDENT_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/applications', name: RouteEnum::STUDENT_APPLICATION_LIST)]
    public function applicationList(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::STUDENT_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/verify/email', name: RouteEnum::STUDENT_VERIFY_EMAIL)]
    public function verifyEmail(
        Request $request,
        StudentEmailVerifier $emailVerifier,
        StudentRepository $repository
    ): Response {
        $id = $request->get('id');

        if ($id === null) {
            return $this->redirectToRoute(RouteEnum::STUDENT_REGISTER);
        }

        $user = $repository->find($id);

        if ($user === null) {
            return $this->redirectToRoute(RouteEnum::STUDENT_REGISTER);
        }

        try {
            $emailVerifier->handleEmailConfirmation($request->getUri(), $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            // todo add logs
            return $this->redirectToRoute(RouteEnum::STUDENT_REGISTER);
        }

        return $this->redirectToRoute(RouteEnum::STUDENT_HOME);
    }
}
