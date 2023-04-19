<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Campus\Add;

use App\Domain\Core\Flusher;
use App\Domain\Core\UuidGenerator;
use App\Domain\School\Entity\Campus\Campus;
use App\Domain\School\Entity\Campus\CampusId;
use App\Domain\School\Repository\CampusRepository;

class Handler
{
    public function __construct(
        private readonly CampusRepository $campusRepository,
        private readonly Flusher $flusher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function handle(Command $command): void
    {
        $campus = new Campus(
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
