<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Confirm;

use App\Domain\Core\Flusher;
use App\Domain\Core\UuidGenerator;
use App\Domain\School\Entity\School\InvitationToken;
use App\Domain\School\Repository\SchoolRepository;
use App\Domain\School\Service\SchoolConfirmedEmailSender;
use DateTimeImmutable;

class Handler
{
    public function __construct(
        private readonly SchoolRepository $schoolRepository,
        private readonly Flusher $flusher,
        private readonly UuidGenerator $uuidGenerator,
        private readonly SchoolConfirmedEmailSender $emailSender,
    ) {
    }

    public function handle(Command $command): void
    {
        $school = $this->schoolRepository->get($command->id);
        $token = $this->uuidGenerator->generate();
        $school->confirmRegister(
            new InvitationToken($token, new DateTimeImmutable())
        );

        $admin = $school->getAdmin();
        $this->emailSender->send($admin->getEmail()->getValue(), $token, $school->getId());

        $this->flusher->flush();
    }
}
