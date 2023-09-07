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

    public function create(
        string $name = null,
        string $surname = null,
        string $email = null
    ): Student {
        $student = Student::register(
            new StudentId($this->uuidGenerator->generate()),
            $email ?? self::EMAIL,
            $name ?? self::NAME,
            $surname ?? self::SURNAME,
            $this->hasherFactory->getPasswordHasher(StaffMember::class)
                ->hash(self::PASSWORD)
        );
        $student->verifyEmail();

        return $student;
    }
}
