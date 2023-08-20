<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Core\Common\UuidGenerator;
use App\Core\School\Entity\School\StaffMember;
use App\Core\Student\Entity\Student\Student;
use App\Core\Student\Entity\Student\StudentId;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class StudentUserFixtures extends AbstractUserGroupFixtures
{
    private const PASSWORD = 'student';
    private const EMAIL = 'student@student.student';
    private const NAME = 'student';
    private const SURNAME = 'student';

    public function __construct(
        private readonly PasswordHasherFactoryInterface $hasherFactory,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $student = Student::register(
            new StudentId($this->uuidGenerator->generate()),
            self::EMAIL,
            self::NAME,
            self::SURNAME,
            $this->hasherFactory->getPasswordHasher(StaffMember::class)
                ->hash(self::PASSWORD)
        );
        $student->verifyEmail();

        $manager->persist($student);
        $manager->flush();
    }
}
