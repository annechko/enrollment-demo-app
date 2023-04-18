<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Core\UuidGenerator;
use App\Domain\School\Entity\School\Email;
use App\Domain\School\Entity\School\InvitationToken;
use App\Domain\School\Entity\School\Name;
use App\Domain\School\Entity\School\School;
use App\Domain\School\Entity\School\SchoolId;
use App\Domain\School\Entity\School\StaffMember;
use App\Domain\School\Entity\School\StaffMemberId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class SchoolAdminUserFixtures extends Fixture
{
    public const PASSWORD = 'school';
    public const EMAIL = 'school@school.school';

    public function __construct(
        private readonly PasswordHasherFactoryInterface $hasherFactory,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $school = School::register(
            new SchoolId($this->uuidGenerator->generate()),
            new Name('default school'),
            new StaffMemberId($this->uuidGenerator->generate()),
            'school admin name',
            'school admin lastname',
            new Email(self::EMAIL)
        );
        $school->confirmRegister(
            new InvitationToken(
                $this->uuidGenerator->generate(),
                new \DateTimeImmutable()
            )
        );
        $school->getAdmin()->confirmAccount(
            $this->hasherFactory->getPasswordHasher(StaffMember::class)
                ->hash(self::PASSWORD)
        );

        $manager->persist($school);
        $manager->flush();
    }
}
