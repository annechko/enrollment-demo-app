<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Intake\Remove;

use App\Domain\Core\Flusher;
use App\Domain\School\Entity\Course\CourseId;
use App\Domain\School\Entity\Course\Intake\IntakeId;
use App\Domain\School\Repository\CourseRepository;

class Handler
{
    public function __construct(
        private readonly CourseRepository $courseRepository,
        private readonly Flusher $flusher,
    ) {
    }

    public function handle(Command $command): void
    {
        $course = $this->courseRepository->get(new CourseId($command->courseId));
        $course->removeIntake(new IntakeId($command->intakeId));

        $this->flusher->flush();
    }
}
