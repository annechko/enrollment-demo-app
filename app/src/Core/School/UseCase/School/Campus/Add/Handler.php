<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Campus\Add;

use App\Core\Common\Flusher;
use App\Core\Common\UuidGenerator;
use App\Core\School\Entity\Campus\Campus;
use App\Core\School\Entity\Campus\CampusId;
use App\Core\School\Entity\School\SchoolId;
use App\Core\School\Repository\CampusRepository;
use App\Core\School\Repository\SchoolRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class Handler
{
    public function __construct(
        private readonly SchoolRepository $schoolRepository,
        private readonly CampusRepository $campusRepository,
        private readonly Flusher $flusher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    #[AsMessageHandler]
    public function handle(Command $command): void
    {
        $school = $this->schoolRepository->get(new SchoolId($command->schoolId));
        $campus = new Campus(
            $school->getId(),
            new CampusId($this->uuidGenerator->generate()),
            $command->name,
            $command->address
        );
        if ($this->campusRepository->hasByName($command->name)) {
            throw new \DomainException("Campus with the name \"$command->name\" already exists.");
        }
        $this->campusRepository->add($campus);

        $this->flusher->flush();
    }
}
