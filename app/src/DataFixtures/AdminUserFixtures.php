<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DbDefaultData\AdminUserCreator;
use Doctrine\Persistence\ObjectManager;

class AdminUserFixtures extends AbstractUserGroupFixtures
{
    public function __construct(private readonly AdminUserCreator $adminUserCreator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->adminUserCreator->create();

        $manager->persist($user);
        $manager->flush();
    }
}
