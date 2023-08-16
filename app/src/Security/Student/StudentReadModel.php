<?php

declare(strict_types=1);

namespace App\Security\Student;

use App\Domain\Student\Entity\Student\Student;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class StudentReadModel implements UserInterface, PasswordAuthenticatedUserInterface, EquatableInterface
{
    /**
     * @param string[] $roles
     */
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $passwordHash,
        public readonly string $name,
        public readonly string $surname,
        public readonly bool $isEmailVerified,
        public readonly array $roles,
    ) {
    }

    public static function createFromStudent(Student $student): self
    {
        return new StudentReadModel(
            $student->getId()->getValue(),
            $student->getEmail(),
            $student->getPasswordHash(),
            $student->getName(),
            $student->getSurname(),
            $student->isEmailVerified(),
            $student->getRoles(),
        );
    }

    /**
     * @return string[]
     */
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

    public function isEmailVerified(): bool
    {
        return $this->isEmailVerified;
    }

    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }
        $areRolesEqual = count($this->roles) === count($user->roles)
                         && array_diff($this->roles, $user->roles)
                            === array_diff($user->roles, $this->roles);

        return
            $this->id === $user->id &&
            $this->email === $user->email &&
            $this->passwordHash === $user->passwordHash &&
            $areRolesEqual;
    }
}
