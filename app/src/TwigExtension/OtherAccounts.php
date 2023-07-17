<?php

declare(strict_types=1);

namespace App\TwigExtension;

use App\Infrastructure\RouteEnum;
use App\Security\AdminReadModel;
use App\Security\SchoolStaffMemberReadModel;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OtherAccounts extends AbstractExtension
{
    private \Symfony\Component\HttpFoundation\Session\SessionInterface $session;
    private ?string $currentUserIdentifier;

    public function __construct(
        readonly RequestStack $requestStack,
        readonly Security $security,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
        $this->session = $requestStack->getSession();
        $this->currentUserIdentifier = $security->getUser()?->getUserIdentifier();
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'other_accounts',
                [$this, 'otherAccounts'],
                ['needs_environment' => false, 'is_safe' => ['html']]
            ),
        ];
    }

    public function otherAccounts(): string
    {
        $accounts = [];
        // todo in case of FRAMEWORK UPDATE - check this functionality!
        // I really don't like this solution, it's very fragile,
        // if you know a better way - tell me please, would really appreciate it.
        foreach ($this->session->getIterator() as $name => $item) {
            if ($name === '_security_main' || $name === '_security_school') {
                try {
                    $token = unserialize($item);
                    if ($token instanceof PostAuthenticationToken) {
                        $user = $token->getUser();
                        if ($user instanceof SchoolStaffMemberReadModel
                            || $user instanceof AdminReadModel
                        ) {
                            if ($user->getUserIdentifier() === $this->currentUserIdentifier) {
                                continue;
                            }
                            $url = $this->urlGenerator->generate(
                                $user instanceof AdminReadModel ? RouteEnum::ADMIN_HOME : 'school_home'
                            );

                            $accounts[] = [
                                'email' => $user->getUserIdentifier(),
                                'home' => $url,
                            ];
                        }
                    }
                } catch (\Throwable $exception) {
                }
            }
        }
        return json_encode($accounts);
    }
}
