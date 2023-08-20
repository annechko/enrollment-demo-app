<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Intake\Edit;

use App\Domain\Common\Flusher;
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
    ) {
    }

    public function handle(Command $command): void
    {
        $course = $this->courseRepository->get(new CourseId($command->courseId));
        $campus = null;
        if (!empty($command->campusId)) {
            $campus = $this->campusRepository->get(new CampusId($command->campusId));
        }
        $course->editIntake(
            new IntakeId($command->intakeId),
            $command->startDate,
            $command->endDate,
            $command->name,
            (int) $command->classSize,
            $campus
        );

        $this->flusher->flush();
    }
}
