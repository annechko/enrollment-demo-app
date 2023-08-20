<?php

declare(strict_types=1);

namespace App\Core\Student\UseCase\Application\Add;

use App\Core\Common\Flusher;
use App\Core\Common\UuidGenerator;
use App\Core\School\Entity\Course\CourseId;
use App\Core\School\Entity\Course\Intake\IntakeId;
use App\Core\School\Entity\School\SchoolId;
use App\Core\School\Repository\CourseRepository;
use App\Core\School\Repository\SchoolRepository;
use App\Core\Student\Entity\Application\Application;
use App\Core\Student\Entity\Application\ApplicationId;
use App\Core\Student\Entity\Student\StudentId;
use App\Core\Student\Repository\ApplicationRepository;
use App\Core\Student\Repository\StudentRepository;

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
