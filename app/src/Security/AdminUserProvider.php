<?php

declare(strict_types=1);

namespace App\Security;

use App\ReadModel\Admin\AdminUser\AdminUserFetcher;
use App\ReadModel\Admin\AdminUser\AdminUserReadModel;
use App\Repository\AdminUserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AdminUserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private readonly AdminUserFetcher $userFetcher,
        private readonly AdminUserRepository $userRepository,
    ) {
    }

    public function upgradePassword(
        PasswordAuthenticatedUserInterface $user,
        string $newHashedPassword
    ): void {
        $this->userRepository->upgradePassword($user, $newHashedPassword);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === AdminUserReadModel::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userFetcher->findByEmail($identifier);
        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
