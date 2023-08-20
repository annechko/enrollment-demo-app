<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DbDefaultData\SchoolDataCreator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class SchoolFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private readonly SchoolDataCreator $schoolDataCreator,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $entities = $this->schoolDataCreator->createEntities();
        foreach ($entities as $entity) {
            $manager->persist($entity);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['school'];
    }
}
