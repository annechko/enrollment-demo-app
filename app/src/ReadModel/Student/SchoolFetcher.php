<?php

declare(strict_types=1);

namespace App\ReadModel\Student;

use App\Domain\School\Entity\Course\Course;
use App\Domain\School\Entity\Course\Intake\Intake;
use App\Domain\School\Entity\School\School;
use App\ReadModel\Student\Filter\CourseFilter;
use App\ReadModel\Student\Filter\IntakeFilter;
use App\ReadModel\Student\Filter\SchoolFilter;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class SchoolFetcher
{
    private string $tableSchool;
    private string $tableCourse;
    private string $tableIntake;

    public function __construct(
        private readonly Connection $connection,
        private readonly PaginatorInterface $paginator,
        readonly EntityManagerInterface $em
    ) {
        $this->tableSchool = $em->getClassMetadata(School::class)->getTableName();
        $this->tableCourse = $em->getClassMetadata(Course::class)->getTableName();
        $this->tableIntake = $em->getClassMetadata(Intake::class)->getTableName();
    }

    /**
     * @return array<array<string>>
     */
    public function fetchSchools(
        SchoolFilter $filter,
        int $size,
    ): array {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                's.id',
                's.name',
            )
            ->from($this->tableSchool, 's');

        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('LOWER(s.name)', ':name'));
            $qb->setParameter('name', '%' . mb_strtolower($filter->name) . '%');
        }

        $qb->andWhere('s.status = :status');
        $qb->setParameter('status', School::STATUS_ACTIVE);

        $qb->orderBy('LOWER(s.name)', 'asc')
            ->setMaxResults($size);

        return $qb->fetchAllAssociative();
    }

    /**
     * @return array<array<string>>
     * @throws \Doctrine\DBAL\Exception
     */
    public function fetchSchoolCourses(
        CourseFilter $filter,
        int $size,
    ): array {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'c.id',
                'c.name',
            )
            ->andWhere('s.status = :status')
            ->setParameter('status', School::STATUS_ACTIVE)
            ->andWhere('c.school_id = :school_id')
            ->setParameter('school_id', $filter->schoolId)
            ->from($this->tableCourse, 'c')
            ->innerJoin('c', $this->tableSchool, 's', 'c.school_id = s.id');

        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('LOWER(c.name)', ':name'));
            $qb->setParameter('name', '%' . mb_strtolower($filter->name) . '%');
        }

        $qb->orderBy('LOWER(c.name)', 'asc')
            ->setMaxResults($size);

        return $qb->fetchAllAssociative();
    }

    /**
     * @return array<array<string>>
     * @throws \Doctrine\DBAL\Exception
     */
    public function fetchCourseIntakes(
        IntakeFilter $filter,
    ): array {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'i.id',
                'i.name',
                'i.start_date',
                'i.end_date',
            )
            ->andWhere('s.status = :status')
            ->setParameter('status', School::STATUS_ACTIVE)
            ->andWhere('c.id = :course_id')
            ->setParameter('course_id', $filter->courseId)
            ->andWhere('s.id = :school_id')
            ->setParameter('school_id', $filter->schoolId)
            ->andWhere('i.start_date > :start_date')
            ->setParameter('start_date', (new \DateTimeImmutable())->format('Y-m-d'))
            ->from($this->tableIntake, 'i')
            ->innerJoin('i', $this->tableCourse, 'c', 'i.course_id = c.id')
            ->innerJoin('c', $this->tableSchool, 's', 'c.school_id = s.id');

        $qb->orderBy('start_date', 'asc');

        return $qb->fetchAllAssociative();
    }
}
