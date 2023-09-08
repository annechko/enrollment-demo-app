<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Register;

use App\Core\Common\Flusher;
use App\Core\Common\UuidGenerator;
use App\Core\School\Entity\School\Email;
use App\Core\School\Entity\School\Name;
use App\Core\School\Entity\School\School;
use App\Core\School\Entity\School\SchoolId;
use App\Core\School\Entity\School\StaffMemberId;
use App\Core\School\Entity\School\StaffMemberName;
use App\Core\School\Repository\SchoolRepository;
use App\Core\School\Repository\SchoolStaffMemberRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class Handler
{
    public function __construct(
        private readonly SchoolRepository $schoolRepository,
        private readonly SchoolStaffMemberRepository $staffMemberRepository,
        private readonly Flusher $flusher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    #[AsMessageHandler]
    public function handle(Command $command): void
    {
        if ($this->staffMemberRepository->hasByEmail($command->adminEmail)) {
            throw new \InvalidArgumentException('School with this admin email already exists.');
        }
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
