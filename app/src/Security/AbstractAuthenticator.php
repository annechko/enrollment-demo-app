<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

abstract class AbstractAuthenticator extends AbstractLoginFormAuthenticator
{
    abstract protected function getLoginRoute(): string;

    abstract protected function getAfterLoginRoute(): string;

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly AbstractUserProvider $userProvider,
    ) {
    }

    /**
     * @return array<string, string>
     */
    private function getCredentials(Request $request): array
    {
        $data = \json_decode($request->getContent(), true) ?? [];

        $credentials = [];
        $credentials['email'] = $data['email'] ?? '';
        $credentials['password'] = $data['password'] ?? '';

        return $credentials;
    }

    public function authenticate(Request $request): Passport
    {
        $credentials = $this->getCredentials($request);
        $email = $credentials['email'];

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email, $this->userProvider->loadUserByIdentifier(...)),
            new PasswordCredentials($credentials['password'])
        );
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): Response {
        if ($exception->getPrevious() instanceof UserNotFoundException) {
            return new JsonResponse(
                [
                    'error' => 'User not found.',
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        return new JsonResponse(
            [
                'error' => $exception->getMessageKey(),
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): ?Response {
        $url = $this->urlGenerator->generate($this->getAfterLoginRoute());
        if ($request->getContentTypeFormat() === 'json') {
            return new JsonResponse(
                [
                    'redirect' => $url,
                ]
            );
        }

        return new RedirectResponse($url);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate($this->getLoginRoute());
    }
}
