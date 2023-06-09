<?php

declare(strict_types=1);

namespace App\Controller\School;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/school')]
class LoginFormController extends AbstractController
{
    #[Route(path: '/login', name: 'school_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render(
            'school/index.html.twig',
            ['title' => 'Sign in', 'error' => $error]
        );
    }

    #[Route(path: '/logout', name: 'school_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
