<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Add;

use App\Domain\Common\Flusher;
use App\Domain\Common\UuidGenerator;
use App\Domain\School\Entity\Course\Course;
use App\Domain\School\Entity\Course\CourseId;
use App\Domain\School\Entity\School\SchoolId;
use App\Domain\School\Repository\CourseRepository;
use App\Domain\School\Repository\SchoolRepository;

class Handler
{
    public function __construct(
        private readonly SchoolRepository $schoolRepository,
        private readonly CourseRepository $courseRepository,
        private readonly Flusher $flusher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function handle(Command $command): CourseId
    {
        $school = $this->schoolRepository->get(new SchoolId($command->schoolId));
        $course = new Course(
            $school->getId(),
            new CourseId($this->uuidGenerator->generate()),
            $command->name,
            $command->description,
        );

        $this->courseRepository->add($course);

        $this->flusher->flush();

        return $course->getId();
    }
}
