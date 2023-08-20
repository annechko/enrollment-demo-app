<?php

declare(strict_types=1);

namespace App\Domain\Student\UseCase\Application\Add;

use App\Domain\Common\Flusher;
use App\Domain\Common\UuidGenerator;
use App\Domain\School\Entity\Course\CourseId;
use App\Domain\School\Entity\Course\Intake\IntakeId;
use App\Domain\School\Entity\School\SchoolId;
use App\Domain\School\Repository\CourseRepository;
use App\Domain\School\Repository\SchoolRepository;
use App\Domain\Student\Entity\Application\Application;
use App\Domain\Student\Entity\Application\ApplicationId;
use App\Domain\Student\Entity\Student\StudentId;
use App\Domain\Student\Repository\ApplicationRepository;
use App\Domain\Student\Repository\StudentRepository;

class Handler
{
    public function __construct(
        private readonly StudentRepository $studentRepository,
        private readonly SchoolRepository $schoolRepository,
        private readonly CourseRepository $courseRepository,
        private readonly ApplicationRepository $applicationRepository,
        private readonly Flusher $flusher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function handle(Command $command): void
    {
        $student = $this->studentRepository->get(new StudentId($command->studentId));
        $school = $this->schoolRepository->get(new SchoolId($command->schoolId));
        $course = $this->courseRepository->get(new CourseId($command->courseId));
        $intake = $course->getIntake(new IntakeId($command->intakeId));

        if ($intake->getStartDate() < new \DateTimeImmutable()) {
            throw new \DomainException('Intake already started.');
        }

        $application = new Application(
            new ApplicationId($this->uuidGenerator->generate()),
            $student,
            $school,
            $course,
            $intake,
            $command->passportNumber,
            $command->passportExpiry,
            $command->dateOfBirth,
            $command->fullName,
            $command->preferredName,
        );

        $this->applicationRepository->add($application);

        $this->flusher->flush();
    }
}
