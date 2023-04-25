<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Add;

use App\Domain\Core\Flusher;
use App\Domain\Core\UuidGenerator;
use App\Domain\School\Entity\Course\Course;
use App\Domain\School\Entity\Course\CourseId;
use App\Domain\School\Repository\CourseRepository;
use DomainException;

class Handler
{
    public function __construct(
        private readonly CourseRepository $courseRepository,
        private readonly Flusher $flusher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function handle(Command $command): void
    {
        $course = new Course(
            new CourseId($this->uuidGenerator->generate()),
            $command->name,
            $command->description
        );
        if ($this->courseRepository->hasByName($command->name)) {
            throw new DomainException("Course with the name \"$command->name\" already exists.");
        }
        $this->courseRepository->add($course);

        $this->flusher->flush();
    }
}
