<?php

declare(strict_types=1);

namespace App\ReadModel\Admin\AdminUser;

use App\Domain\School\Common\RoleEnum;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminUserReadModel implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $name,
        public readonly ?string $surname,
        public readonly string $roles,
    ) {
    }

    public function getRoles(): array
    {
        return [RoleEnum::ADMIN_USER->value];
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
        return $this->password;
    }
}
