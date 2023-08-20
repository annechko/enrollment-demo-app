<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Profile\Edit;

use App\Core\Common\Flusher;
use App\Core\School\Entity\School\Name;
use App\Core\School\Entity\School\SchoolId;
use App\Core\School\Entity\School\StaffMemberId;
use App\Core\School\Repository\SchoolRepository;
use App\Core\School\Repository\SchoolStaffMemberRepository;

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
            throw new \DomainException('Member not found.');
        }

        $school->edit(new Name($command->name), $staffMember->getId());

        $this->flusher->flush();
    }
}
