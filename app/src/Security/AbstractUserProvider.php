<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

abstract class AbstractUserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private readonly AbstractUserDbHelper $userFetcher,
        private readonly string $userClassName
    ) {
    }

    public function upgradePassword(
        PasswordAuthenticatedUserInterface $user,
        string $newHashedPassword
    ): void {
        if (!$user instanceof ($this->userClassName) || (!$user instanceof UserInterface)) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', \get_class($user))
            );
        }

        $this->userFetcher->runUpgradePassword($user->getUserIdentifier(), $newHashedPassword);

        // in a rare occasion when updating hashing strategy in symfony (if I update composer packages one day)
        // sorry but users will need to log in again once, because I can't update my read-only user model
        // and symfony doesn't allow me to replace existing user object.
        throw new ReloginRequiredException();
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof ($this->userClassName)) {
            throw new UnsupportedUserException('Invalid user class ' . \get_class($user));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return $class === ($this->userClassName);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->userFetcher->findByEmail($identifier);
    }

}
