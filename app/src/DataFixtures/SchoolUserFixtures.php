<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DbDefaultData\SchoolDefaultAccountCreator;
use Doctrine\Persistence\ObjectManager;

class SchoolUserFixtures extends AbstractUserGroupFixtures
{
    public function __construct(
        private readonly SchoolDefaultAccountCreator $userCreator,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $school = $this->userCreator->create();

        $manager->persist($school);
        $manager->flush();
    }
}
