<?php

declare(strict_types=1);

namespace App\Security;

use App\Domain\Student\Repository\StudentRepository;
use App\ReadModel\Student\StudentFetcher;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class StudentProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private readonly StudentFetcher $userFetcher,
        private readonly StudentRepository $userRepository,
    ) {
    }

    public function upgradePassword(
        PasswordAuthenticatedUserInterface $user,
        string $newHashedPassword
    ): void {
        if (!$user instanceof StudentReadModel) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', \get_class($user))
            );
        }

        $admin = $this->userRepository->find($user->id);
        $admin->upgradePasswordHash($newHashedPassword);

        $this->userRepository->save($admin, true);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof StudentReadModel) {
            throw new UnsupportedUserException('Invalid user class ' . \get_class($user));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return $class === StudentReadModel::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $userFields = $this->userFetcher->findByEmail($identifier);
        if (count($userFields) === 0) {
            throw new UserNotFoundException();
        }
        try {
            return new StudentReadModel(
                $userFields['id'],
                $userFields['email'],
                $userFields['password_hash'],
                $userFields['name'],
                $userFields['surname'],
                $userFields['is_email_verified'],
                \json_decode($userFields['roles'], true, 512, JSON_THROW_ON_ERROR)
            );
        } catch (\JsonException $exception) {
            throw new UserNotFoundException($exception->getMessage());
        } catch (\Throwable) {
            throw new UserNotFoundException();
        }
    }
}
