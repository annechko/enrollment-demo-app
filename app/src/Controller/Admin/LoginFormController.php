<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Infrastructure\RouteEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/admin')]
class LoginFormController extends AbstractController
{
    #[Route(path: '/login', name: RouteEnum::ADMIN_LOGIN)]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute(RouteEnum::ADMIN_HOME);
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'admin/index.html.twig',
            ['last_username' => $lastUsername, 'error' => $error]
        );
    }

    #[Route(path: '/logout', name: 'admin_logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}
