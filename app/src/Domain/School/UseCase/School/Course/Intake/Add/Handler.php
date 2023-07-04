<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Intake\Add;

use App\Domain\Core\Flusher;
use App\Domain\Core\UuidGenerator;
use App\Domain\School\Entity\Campus\CampusId;
use App\Domain\School\Entity\Course\CourseId;
use App\Domain\School\Entity\Course\Intake\IntakeId;
use App\Domain\School\Repository\CampusRepository;
use App\Domain\School\Repository\CourseRepository;

class Handler
{
    public function __construct(
        private readonly CourseRepository $courseRepository,
        private readonly CampusRepository $campusRepository,
        private readonly Flusher $flusher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function handle(Command $command): void
    {
        $course = $this->courseRepository->get(new CourseId($command->courseId));
        $campus = $this->campusRepository->get(new CampusId($command->campusId));
        $course->addIntake(
            new IntakeId($this->uuidGenerator->generate()),
            $command->startDate,
            $command->endDate,
            $command->name,
            $command->classSize,
            $campus
        );

        $this->flusher->flush();
    }
}
