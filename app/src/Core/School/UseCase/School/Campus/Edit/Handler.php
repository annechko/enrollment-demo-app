<?php

declare(strict_types=1);

namespace App\Core\School\UseCase\School\Campus\Edit;

use App\Core\Common\Flusher;
use App\Core\School\Entity\Campus\CampusId;
use App\Core\School\Repository\CampusRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class Handler
{
    public function __construct(
        private readonly CampusRepository $campusRepository,
        private readonly Flusher $flusher,
    ) {
    }

    #[AsMessageHandler]
    public function handle(Command $command): void
    {
        $campus = $this->campusRepository->get(new CampusId($command->campusId));
        $campus->edit($command->name, $command->address);

        $campusWithSameName = $this->campusRepository->findByName($command->name);
        if ($campusWithSameName && !$campusWithSameName->getId()->isSameValue($command->campusId)) {
            throw new \DomainException("Campus with the name \"$command->name\" already exists.");
        }
        $this->flusher->flush();
    }
}
