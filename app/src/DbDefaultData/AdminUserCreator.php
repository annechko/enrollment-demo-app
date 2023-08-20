<?php

declare(strict_types=1);

namespace App\DbDefaultData;

use App\Core\Admin\Entity\AdminUser\AdminUser;
use App\Core\Admin\Entity\AdminUser\AdminUserId;
use App\Core\Common\UuidGenerator;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AdminUserCreator
{
    private const PASSWORD = 'admin';
    private const EMAIL = 'admin@example.com';

    public function __construct(
        private readonly PasswordHasherFactoryInterface $hasherFactory,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function create(): AdminUser
    {
        $user = new AdminUser(
            new AdminUserId($this->uuidGenerator->generate()),
            self::EMAIL,
            $this->hasherFactory->getPasswordHasher(AdminUser::class)
                ->hash(self::PASSWORD)
        );

        return $user;
    }
}
