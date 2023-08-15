<?php

declare(strict_types=1);

namespace App\ReadModel\Student;

use App\Domain\School\Entity\Course\Course;
use App\Domain\School\Entity\Course\Intake\Intake;
use App\Domain\School\Entity\School\School;
use App\Domain\Student\Entity\Application\Application;
use App\Domain\Student\Entity\Student\StudentId;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class ApplicationFetcher
{
    private Connection $connection;
    private string $tableNameApplications;
    private string $tableNameCourse;
    private string $tableNameSchool;
    private string $tableNameIntake;

    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->tableNameApplications = $em->getClassMetadata(Application::class)->getTableName();
        $this->tableNameCourse = $em->getClassMetadata(Course::class)->getTableName();
        $this->tableNameSchool = $em->getClassMetadata(School::class)->getTableName();
        $this->tableNameIntake = $em->getClassMetadata(Intake::class)->getTableName();
    }

    /**
     * @return array<string, mixed>
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function findAll(StudentId $studentId): array
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                'a.id AS id',
                'a.status AS status',
                'a.created_at AS created_at',
                's.name AS school_name',
                's.id AS school_id',
                'c.name AS course_name',
                'c.id AS course_id',
                'i.start_date AS intake_start_date',
                'i.end_date AS intake_end_date',
            )
            ->from($this->tableNameApplications, 'a')
            ->innerJoin('a', $this->tableNameSchool, 's', 's.id = a.school_id')
            ->innerJoin('a', $this->tableNameCourse, 'c', 'c.id = a.course_id')
            ->innerJoin('a', $this->tableNameIntake, 'i', 'i.id = a.intake_id')
            ->where('student_id = :student_id')
            ->setParameter('student_id', $studentId)
            ->orderBy('a.created_at')
            ->fetchAllAssociative();

        return $result;
    }
}
