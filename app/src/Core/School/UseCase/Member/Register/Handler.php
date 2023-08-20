<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\Member\Register;

use App\Core\Common\Flusher;
use App\Core\School\Entity\School\SchoolId;
use App\Core\School\Entity\School\StaffMember;
use App\Core\School\Repository\SchoolRepository;
use App\Core\School\Repository\SchoolStaffMemberRepository;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class Handler
{
    public function __construct(
        private readonly SchoolRepository $schoolRepository,
        private readonly SchoolStaffMemberRepository $staffMemberRepository,
        private readonly Flusher $flusher,
        private readonly PasswordHasherFactoryInterface $hasherFactory,
    ) {
    }

    public function handle(Command $command): void
    {
        $school = $this->schoolRepository->get(new SchoolId($command->schoolId));
        $member = $this->staffMemberRepository->getByInvitationToken($command->invitationToken);
        if (!$member->isFromSchool($school)) {
            throw new \DomainException('Staff member not found.');
        }

        $member->confirmAccount(
            $this->hasherFactory->getPasswordHasher(StaffMember::class)
                ->hash($command->plainPassword)
        );

        $this->flusher->flush();
    }
}
