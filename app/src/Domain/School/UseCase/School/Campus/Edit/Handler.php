<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Campus\Edit;

use App\Domain\Core\Flusher;
use App\Domain\School\Repository\CampusRepository;

class Handler
{
    public function __construct(
        private readonly CampusRepository $campusRepository,
        private readonly Flusher $flusher,
    ) {
    }

    public function handle(Command $command): void
    {
        $campus = $this->campusRepository->get($command->id);
        $campus->edit($command->name, $command->address);

        $campusWithSameName = $this->campusRepository->findByName($command->name);
        if ($campusWithSameName && !$campusWithSameName->getId()->isSameValue($command->id)) {
            throw new \DomainException("Campus with the name \"$command->name\" already exists.");
        }
        $this->flusher->flush();
    }
}
