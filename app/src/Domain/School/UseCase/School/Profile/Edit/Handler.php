<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Profile\Edit;

use App\Domain\Common\Flusher;
use App\Domain\School\Entity\School\Name;
use App\Domain\School\Entity\School\SchoolId;
use App\Domain\School\Entity\School\StaffMemberId;
use App\Domain\School\Repository\SchoolRepository;
use App\Domain\School\Repository\SchoolStaffMemberRepository;

class Handler
{
    public function __construct(
        private readonly SchoolRepository $schoolRepository,
        private readonly SchoolStaffMemberRepository $memberRepository,
        private readonly Flusher $flusher,
    ) {
    }

    public function handle(Command $command): void
    {
        $school = $this->schoolRepository->get(new SchoolId($command->schoolId));
        $staffMember = $this->memberRepository->get(new StaffMemberId($command->staffMemberId));
        if (!$staffMember->isFromSchool($school)) {
            throw new \DomainException("Member not found.");
        }

        $school->edit(new Name($command->name), $staffMember->getId());

        $this->flusher->flush();
    }
}
