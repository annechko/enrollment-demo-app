<?php

declare(strict_types=1);

namespace App\Controller\Student;

use App\Infrastructure\RouteEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student')]
class AuthController extends AbstractController
{
    #[Route('/register', name: RouteEnum::STUDENT_REGISTER)]
    public function register(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route(path: '/login', name: RouteEnum::STUDENT_LOGIN)]
    public function login(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute(RouteEnum::STUDENT_HOME);
        }

        return $this->render('base.html.twig');
    }

    #[Route(path: '/logout', name: RouteEnum::STUDENT_LOGOUT)]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
