<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DbDefaultData\StudentUserCreator;
use Doctrine\Persistence\ObjectManager;

class StudentUserFixtures extends AbstractUserGroupFixtures
{
    public function __construct(
        private readonly StudentUserCreator $userCreator,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $student = $this->userCreator->create();

        $manager->persist($student);
        $manager->flush();
    }
}
