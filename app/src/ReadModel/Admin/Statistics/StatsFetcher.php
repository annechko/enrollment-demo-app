<?php

declare(strict_types=1);

namespace App\ReadModel\Admin\Statistics;

use App\Core\School\Entity\School\School;
use App\Core\Student\Entity\Application\Application;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class StatsFetcher
{
    private const FORMAT_BY_MONTH = 'YY-MM';
    private const FORMAT_BY_DAY = 'MM-DD';

    public function __construct(
        private readonly Connection $connection,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function fetchSchoolRegistrationsByMonth(
        \DateTimeImmutable $since,
    ): array {
        return $this->fetchStats(
            $this->em->getClassMetadata(School::class)->getTableName(),
            self::FORMAT_BY_MONTH,
            $since
        );
    }

    public function fetchStudentApplicationsByDay(\DateTimeImmutable $since): array
    {
        return $this->fetchStats(
            $this->em->getClassMetadata(Application::class)->getTableName(),
            self::FORMAT_BY_DAY,
            $since
        );
    }

    public function fetchSchoolRegistrationsByDay(
        \DateTimeImmutable $since,
    ): array {
        return $this->fetchStats(
            $this->em->getClassMetadata(School::class)->getTableName(),
            self::FORMAT_BY_DAY,
            $since
        );
    }

    public function fetchStudentApplicationsByMonth(\DateTimeImmutable $since): array
    {
        return $this->fetchStats(
            $this->em->getClassMetadata(Application::class)->getTableName(),
            self::FORMAT_BY_MONTH,
            $since
        );
    }

    private function fetchStats(string $table, string $format, \DateTimeImmutable $since): array
    {
        $results = $this->connection->executeQuery(
            "
            SELECT TO_CHAR(created_at, :format) AS period, COUNT(id) AS amount
            FROM $table
            WHERE created_at > :since
            GROUP BY 1
            ORDER BY 1;",
            [
                'since' => $since->format('Y-m-d H:i:s'),
                'format' => $format,
            ]
        )->fetchAllKeyValue();

        return $results;
    }
}
