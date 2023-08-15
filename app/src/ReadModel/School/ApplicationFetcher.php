<?php

declare(strict_types=1);

namespace App\ReadModel\School;

use App\Domain\School\Entity\Course\Course;
use App\Domain\School\Entity\Course\Intake\Intake;
use App\Domain\School\Entity\School\SchoolId;
use App\Domain\Student\Entity\Application\Application;
use App\Domain\Student\Entity\Application\ApplicationId;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class ApplicationFetcher
{
    private Connection $connection;
    private string $tableNameApplication;
    private string $tableNameCourse;
    private string $tableNameIntake;

    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->tableNameApplication = $em->getClassMetadata(Application::class)->getTableName();
        $this->tableNameCourse = $em->getClassMetadata(Course::class)->getTableName();
        $this->tableNameIntake = $em->getClassMetadata(Intake::class)->getTableName();
    }

    /**
     * @return array<string, mixed>
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function findAll(SchoolId $schoolId): array
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                'a.id AS id',
                'a.student_id AS student_id',
                'a.full_name AS student_name',
                'a.status AS status',
                'a.created_at AS created_at',
                'c.name AS course_name',
                'c.id AS course_id',
                'i.start_date AS intake_start_date',
                'i.end_date AS intake_end_date',
            )
            ->from($this->tableNameApplication, 'a')
            ->innerJoin('a', $this->tableNameCourse, 'c', 'c.id = a.course_id')
            ->innerJoin('a', $this->tableNameIntake, 'i', 'i.id = a.intake_id')
            ->where('a.school_id = :school_id')
            ->setParameter('school_id', $schoolId)
            ->fetchAllAssociative();

        return $result;
    }

    public function findOne(ApplicationId $applicationId, SchoolId $schoolId): array
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'student_id',
                'course_id',
                'intake_id',
                'passport_number',
                'passport_expiry',
                'date_of_birth',
                'full_name',
                'preferred_name',
                'created_at',
            )
            ->from($this->tableNameApplication)
            ->where('school_id = :school_id')
            ->setParameter('school_id', $schoolId)
            ->where('id = :id')
            ->setParameter('id', $applicationId)
            ->orderBy('created_at')
            ->fetchAssociative();

        return $result === false ? [] : $result;
    }
}
