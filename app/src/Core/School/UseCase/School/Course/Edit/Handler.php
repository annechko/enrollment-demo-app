<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Course\Edit;

use App\Core\Common\Flusher;
use App\Core\School\Entity\Course\CourseId;
use App\Core\School\Repository\CourseRepository;

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
        $course->edit($command->name, $command->description);

        $this->flusher->flush();
    }
}
