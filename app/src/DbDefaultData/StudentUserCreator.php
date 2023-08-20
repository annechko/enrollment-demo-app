<?php

declare(strict_types=1);

namespace App\DbDefaultData;

use App\Core\Common\UuidGenerator;
use App\Core\School\Entity\School\StaffMember;
use App\Core\Student\Entity\Student\Student;
use App\Core\Student\Entity\Student\StudentId;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class StudentUserCreator
{
    private const PASSWORD = 'student';
    private const EMAIL = 'student@example.com';
    private const NAME = 'student';
    private const SURNAME = 'student';

    public function __construct(
        private readonly PasswordHasherFactoryInterface $hasherFactory,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function create(): Student
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

        return $student;
    }
}
