<?php

declare(strict_types=1);

namespace App\ReadModel\Admin\Statistics;

use App\Core\School\Entity\School\School;
use App\Core\Student\Entity\Application\Application;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class StatsFetcher
{
    public function __construct(
        private readonly Connection $connection,
        private readonly EntityManagerInterface $em,
    ) {
    }


    public function fetchSchoolRegistrationsByMonth(
        \DateTimeImmutable $since,
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
                'since' => $since->format('Y-m-d H:i:s'),
            ]
        )->fetchAllKeyValue();

        return $results;
    }

    public function fetchSchoolRegistrationsByDay(
        \DateTimeImmutable $since,
    ): array {
        $schools = $this->em->getClassMetadata(School::class)->getTableName();
        $results = $this->connection->executeQuery(
            "
            SELECT TO_CHAR(created_at,'MM-DD') AS date, COUNT(id) AS amount
            FROM $schools
            WHERE created_at > :since
            GROUP BY 1
            ORDER BY 1;",
            [
                'since' => $since->format('Y-m-d H:i:s'),
            ]
        )->fetchAllKeyValue();

        return $results;
    }

    public function fetchStudentApplicationsByDay(\DateTimeImmutable $since): array
    {
        $table = $this->em->getClassMetadata(Application::class)->getTableName();
        $results = $this->connection->executeQuery(
            "
            SELECT TO_CHAR(created_at,'MM-DD') AS date, COUNT(id) AS amount
            FROM $table
            WHERE created_at > :since
            GROUP BY 1
            ORDER BY 1;",
            [
                'since' => $since->format('Y-m-d H:i:s'),
            ]
        )->fetchAllKeyValue();

        return $results;
    }

    public function fetchStudentApplicationsByMonth(\DateTimeImmutable $since): array
    {
        $table = $this->em->getClassMetadata(Application::class)->getTableName();
        $results = $this->connection->executeQuery(
            "
            SELECT TO_CHAR(created_at,'YY-MM') AS date, COUNT(id) AS amount
            FROM $table
            WHERE created_at > :since
            GROUP BY 1
            ORDER BY 1;",
            [
                'since' => $since->format('Y-m-d H:i:s'),
            ]
        )->fetchAllKeyValue();

        return $results;
    }
}
