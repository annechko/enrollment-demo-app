<?php

declare(strict_types=1);

namespace App\ReadModel\School;

use App\Domain\School\Entity\Campus\Campus;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class CampusFetcher
{
    private string $campusTableName;

    public function __construct(
        private readonly Connection $connection,
        EntityManagerInterface $em,
    ) {
        $this->campusTableName = $em->getClassMetadata(Campus::class)->getTableName();
    }

    /**
     * @return array<string, string>
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function getCampusesIdToName(): array
    {
        $res = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'name'
            )
            ->from($this->campusTableName)
            ->orderBy('name')
            ->fetchAllKeyValue();

        return $res;
    }
}
