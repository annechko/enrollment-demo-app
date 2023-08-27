<?php
declare(strict_types=1);

namespace App\Core\School\UseCase\School\Delete;

use App\Core\Common\DefaultUserEnum;
use App\Core\School\Entity\School\SchoolId;
use App\Core\School\Repository\SchoolRepository;

class Handler
{
    public function __construct(
        private readonly SchoolRepository $schoolRepository,
        private readonly SchoolDeleteService $schoolDeleteService,
    ) {
    }

    public function handle(Command $command): void
    {
        $school = $this->schoolRepository->get(new SchoolId($command->schoolId));
        if (DefaultUserEnum::isDefaultSchoolUser($school->getAdmin()->getEmail()->getValue())) {
            throw new \DomainException('Can not delete default data.');
        }

        $this->schoolDeleteService->deleteSchool($school);
    }
}
