<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Campus\Add;

use App\Domain\Common\Flusher;
use App\Domain\Common\UuidGenerator;
use App\Domain\School\Entity\Campus\Campus;
use App\Domain\School\Entity\Campus\CampusId;
use App\Domain\School\Entity\School\SchoolId;
use App\Domain\School\Repository\CampusRepository;
use App\Domain\School\Repository\SchoolRepository;

class Handler
{
    public function __construct(
        private readonly SchoolRepository $schoolRepository,
        private readonly CampusRepository $campusRepository,
        private readonly Flusher $flusher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

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
