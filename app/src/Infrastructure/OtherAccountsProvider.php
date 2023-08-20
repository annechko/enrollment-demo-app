<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Security\Admin\AdminReadModel;
use App\Security\School\SchoolStaffMemberReadModel;
use App\Security\Student\StudentReadModel;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

class OtherAccountsProvider
{
    private ?SessionInterface $session;
    private ?string $currentUserIdentifier;
    private const SESSION_ACCOUNT_NAME_ADMIN = '_security_admin';
    private const SESSION_ACCOUNT_NAME_SCHOOL = '_security_school';
    private const SESSION_ACCOUNT_NAME_STUDENT = '_security_student';
    private const USER_CLASS_TO_HOME_ROUTE = [
        AdminReadModel::class => RouteEnum::ADMIN_HOME,
        SchoolStaffMemberReadModel::class => RouteEnum::SCHOOL_HOME,
        StudentReadModel::class => RouteEnum::STUDENT_HOME,
    ];

    public function __construct(
        readonly RequestStack $requestStack,
        readonly Security $security,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
        try {
            $this->session = $requestStack->getSession();
        } catch (SessionNotFoundException $exception) {
        }
        $this->currentUserIdentifier = $security->getUser()?->getUserIdentifier();
    }

    public function getOtherAccounts(): array
    {
        if ($this->session === null) {
            return [];
        }
        $accounts = [];
        // todo in case of FRAMEWORK UPDATE - check this functionality!
        // I really don't like this solution, it's very fragile,
        // if you know a better way - tell me please, would really appreciate it.
        $items = [
            $this->session->get(self::SESSION_ACCOUNT_NAME_ADMIN),
            $this->session->get(self::SESSION_ACCOUNT_NAME_SCHOOL),
            $this->session->get(self::SESSION_ACCOUNT_NAME_STUDENT),
        ];
        foreach ($items as $item) {
            try {
                $token = unserialize($item);
                if (!($token instanceof PostAuthenticationToken)) {
                    continue;
                }
                $user = $token->getUser();
                $userClass = get_class($user);
                if (in_array($userClass, array_keys(self::USER_CLASS_TO_HOME_ROUTE), true)
                ) {
                    if ($user->getUserIdentifier() === $this->currentUserIdentifier) {
                        continue;
                    }
                    $home = self::USER_CLASS_TO_HOME_ROUTE[$userClass] ?? RouteEnum::HOME;
                    $url = $this->urlGenerator->generate($home);

                    $accounts[] = [
                        'email' => $user->getUserIdentifier(),
                        'home' => $url,
                    ];
                }
            } catch (\Throwable) {
            }
        }

        return $accounts;
    }
}
