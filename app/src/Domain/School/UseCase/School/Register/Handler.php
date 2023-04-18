<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Register;

use App\Domain\Core\Flusher;
use App\Domain\Core\UuidGenerator;
use App\Domain\School\Entity\School\Email;
use App\Domain\School\Entity\School\Name;
use App\Domain\School\Entity\School\School;
use App\Domain\School\Entity\School\SchoolId;
use App\Domain\School\Entity\School\StaffMemberId;
use App\Domain\School\Entity\School\StaffMemberName;
use App\Domain\School\Repository\SchoolRepository;

class Handler
{
    public function __construct(
        private readonly SchoolRepository $schoolRepository,
        private readonly Flusher $flusher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function handle(Command $command): void
    {
        $school = School::register(
            new SchoolId($this->uuidGenerator->generate()),
            new Name($command->name),
            new StaffMemberId($this->uuidGenerator->generate()),
            new StaffMemberName($command->adminName, $command->adminSurname),
            new Email($command->adminEmail),
        );

        $this->schoolRepository->add($school);

        $this->flusher->flush();
    }
}
