<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Course\Intake\Remove;

use App\Core\Common\Flusher;
use App\Core\School\Entity\Course\CourseId;
use App\Core\School\Entity\Course\Intake\IntakeId;
use App\Core\School\Repository\CourseRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class Handler
{
    public function __construct(
        private readonly CourseRepository $courseRepository,
        private readonly Flusher $flusher,
    ) {
    }

    #[AsMessageHandler]
    public function handle(Command $command): void
    {
        $course = $this->courseRepository->get(new CourseId($command->courseId));
        $course->removeIntake(new IntakeId($command->intakeId));

        $this->flusher->flush();
    }
}
