<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Confirm;

use App\Core\Common\Flusher;
use App\Core\Common\UuidGenerator;
use App\Core\School\Entity\School\InvitationToken;
use App\Core\School\Entity\School\SchoolId;
use App\Core\School\Repository\SchoolRepository;
use App\Core\School\Service\SchoolConfirmedEmailSender;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class Handler
{
    public function __construct(
        private readonly SchoolRepository $schoolRepository,
        private readonly Flusher $flusher,
        private readonly UuidGenerator $uuidGenerator,
        private readonly SchoolConfirmedEmailSender $emailSender,
    ) {
    }

    #[AsMessageHandler]
    public function handle(Command $command): void
    {
        $school = $this->schoolRepository->get(new SchoolId($command->schoolId));

        $token = $this->uuidGenerator->generate();
        $school->confirmRegister(
            new InvitationToken($token, new \DateTimeImmutable())
        );

        $admin = $school->getAdmin();
        // in real life I would send emails in a separate process, with RabbitMQ for example.
        $this->emailSender->send($admin->getEmail()->getValue(), $token, $school->getId());

        $this->flusher->flush();
    }
}
