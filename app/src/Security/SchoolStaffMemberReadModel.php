<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SchoolStaffMemberReadModel implements UserInterface, PasswordAuthenticatedUserInterface, EquatableInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $passwordHash,
        public readonly ?string $name,
        public readonly ?string $surname,
        public readonly array $roles,
    ) {
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->passwordHash;
    }

    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }
        $areRolesEqual = count($this->roles) == count($user->roles)
                         && array_diff($this->roles, $user->roles)
                            === array_diff($user->roles, $this->roles);
        return
            $this->id === $user->id &&
            $this->email === $user->email &&
            $this->passwordHash === $user->passwordHash &&
            $areRolesEqual;
    }
}
