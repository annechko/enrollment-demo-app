<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Edit;

use App\Domain\Core\Flusher;
use App\Domain\School\Repository\CourseRepository;
use DomainException;

class Handler
{
    public function __construct(
        private readonly CourseRepository $courseRepository,
        private readonly Flusher $flusher,
    ) {
    }

    public function handle(Command $command): void
    {
        $course = $this->courseRepository->get($command->id);
        $course->edit($command->name, $command->description);

        $courseWithSameName = $this->courseRepository->findByName($command->name);
        if ($courseWithSameName && !$courseWithSameName->getId()->isSameValue($command->id)) {
            throw new DomainException("Course with the name \"$command->name\" already exists.");
        }
        $this->flusher->flush();
    }
}
