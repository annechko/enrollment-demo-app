<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Edit;

use App\Domain\Core\Flusher;
use App\Domain\School\Entity\Campus\CampusId;
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
        $campuses = [];
        if (count($command->campuses) > 0) {
            $campuses = $this->campusRepository->findAllByIds(
                array_map(fn ($id) => new CampusId($id), $command->campuses)
            );
        }

        $course = $this->courseRepository->get($command->id);
        $course->edit($command->name, $command->description, $campuses, $command->startDates);

        $this->flusher->flush();
    }
}
