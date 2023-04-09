<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Admin\Entity\AdminUser;
use App\Domain\School\Common\RoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminUserFixtures extends Fixture
{
    public const PASSWORD = 'admin';

    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new AdminUser();
        $user->setEmail('admin@admin.admin');
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                self::PASSWORD
            )
        );
        $user->setRoles([RoleEnum::ADMIN_USER->value]);

        $manager->persist($user);
        $manager->flush();
    }
}
