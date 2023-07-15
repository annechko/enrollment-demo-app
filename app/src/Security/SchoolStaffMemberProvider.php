<?php

declare(strict_types=1);

namespace App\Security;

use App\Domain\School\Repository\SchoolStaffMemberRepository;
use App\ReadModel\School\SchoolStaffMemberFetcher;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SchoolStaffMemberProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private readonly SchoolStaffMemberFetcher $userFetcher,
        private readonly SchoolStaffMemberRepository $userRepository,
    ) {
    }

    public function upgradePassword(
        PasswordAuthenticatedUserInterface $user,
        string $newHashedPassword
    ): void {
        if (!$user instanceof SchoolStaffMemberReadModel) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', \get_class($user))
            );
        }

        $member = $this->userRepository->find($user->id);
        $member->upgradePasswordHash($newHashedPassword);

        $this->userRepository->save($member, true);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof SchoolStaffMemberReadModel) {
            throw new UnsupportedUserException('Invalid user class ' . \get_class($user));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return $class === SchoolStaffMemberReadModel::class;
    }

    /**
     * @param string $identifier
     * @return \App\Security\SchoolStaffMemberReadModel
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $userFields = $this->userFetcher->findByEmail($identifier);
        if (count($userFields) === 0) {
            throw new UserNotFoundException();
        }
        try {
            return new SchoolStaffMemberReadModel(
                $userFields['id'],
                $userFields['email'],
                $userFields['password_hash'],
                $userFields['name'],
                $userFields['surname'],
                \json_decode($userFields['roles'], true, 512, JSON_THROW_ON_ERROR)
            );
        } catch (\JsonException $exception) {
            throw new UserNotFoundException($exception->getMessage());
        } catch (\Throwable) {
            throw new UserNotFoundException();
        }
    }
}
