<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Admin\Entity\AdminUser;
use App\ReadModel\Admin\AdminUser\AdminUserReadModel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AdminUserFixtures extends Fixture
{
    public const PASSWORD = 'admin';

    public function __construct(
        private readonly PasswordHasherFactoryInterface $hasherFactory,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new AdminUser(
            'admin@admin.admin',
            $this->hasherFactory->getPasswordHasher(AdminUserReadModel::class)
                ->hash(self::PASSWORD)
        );

        $manager->persist($user);
        $manager->flush();
    }
}
