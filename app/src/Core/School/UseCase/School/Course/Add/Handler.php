<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Course\Add;

use App\Core\Common\Flusher;
use App\Core\Common\UuidGenerator;
use App\Core\School\Entity\Course\Course;
use App\Core\School\Entity\Course\CourseId;
use App\Core\School\Entity\School\SchoolId;
use App\Core\School\Repository\CourseRepository;
use App\Core\School\Repository\SchoolRepository;

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
