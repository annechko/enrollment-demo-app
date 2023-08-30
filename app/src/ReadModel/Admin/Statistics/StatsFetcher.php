<?php

declare(strict_types=1);

namespace App\ReadModel\Admin\Statistics;

use App\Core\School\Entity\School\School;
use App\ReadModel\Admin\Statistics\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class StatsFetcher
{
    public function __construct(
        private readonly Connection $connection,
        private readonly EntityManagerInterface $em,
    ) {
    }


    public function fetchSchoolRegistrations(
        Filter $filter,
    ): array {
        $schools = $this->em->getClassMetadata(School::class)->getTableName();
        $results = $this->connection->executeQuery(
            "
            SELECT TO_CHAR(created_at,'YY-MM') AS year_month, COUNT(id) AS amount
            FROM $schools
            WHERE created_at> :since
            GROUP BY 1
            ORDER BY 1;",
            [
                'since' => $filter->since->format('Y-m-d H:i:s'),
            ]
        )->fetchAllKeyValue();

        return $results;
    }
}
