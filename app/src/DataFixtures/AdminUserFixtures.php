<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Admin\Entity\AdminUser\AdminUser;
use App\Domain\Admin\Entity\AdminUser\AdminUserId;
use App\Domain\Core\UuidGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AdminUserFixtures extends Fixture
{
    public const PASSWORD = 'admin';

    public function __construct(
        private readonly PasswordHasherFactoryInterface $hasherFactory,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new AdminUser(
            new AdminUserId($this->uuidGenerator->generate()),
            'admin@admin.admin',
            $this->hasherFactory->getPasswordHasher(AdminUser::class)
                ->hash(self::PASSWORD)
        );

        $manager->persist($user);
        $manager->flush();
    }
}
