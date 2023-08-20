<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Confirm;

use App\Domain\Common\Flusher;
use App\Domain\Common\UuidGenerator;
use App\Domain\School\Entity\School\InvitationToken;
use App\Domain\School\Entity\School\SchoolId;
use App\Domain\School\Repository\SchoolRepository;
use App\Domain\School\Service\SchoolConfirmedEmailSender;

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
        $school = $this->schoolRepository->get(new SchoolId($command->id));

        $token = $this->uuidGenerator->generate();
        $school->confirmRegister(
            new InvitationToken($token, new \DateTimeImmutable())
        );

        $this->flusher->flush();

        $admin = $school->getAdmin();
        // in real life I would send emails in a separate process, with RabbitMQ for example.
        $this->emailSender->send($admin->getEmail()->getValue(), $token, $school->getId());
    }
}
