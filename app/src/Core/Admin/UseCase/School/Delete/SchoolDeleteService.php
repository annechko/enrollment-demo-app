<?php

declare(strict_types=1);

namespace App\Core\Admin\UseCase\School\Delete;

use App\Core\Common\Flusher;
use App\Core\School\Entity\Campus\Campus;
use App\Core\School\Entity\Course\Course;
use App\Core\School\Entity\Course\Intake\Intake;
use App\Core\School\Entity\School\School;
use App\Core\School\Entity\School\StaffMember;
use App\Core\Student\Entity\Application\Application;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class SchoolDeleteService
{
    public function __construct(
        private readonly Connection $connection,
        private readonly Flusher $flusher,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function deleteSchool(School $school): void
    {
        $this->deleteApplications($school);
        $this->deleteIntakes($school);
        $this->deleteCourses($school);
        $this->deleteCampuses($school);
        $this->deleteStaffMembers($school);

        $this->em->remove($school);

        $this->flusher->flush();
    }

    private function deleteApplications(School $school): void
    {
        $table = $this->getTableName(Application::class);
        $this->connection
            ->executeQuery(
                "DELETE FROM $table WHERE school_id = :school_id;",
                ['school_id' => $school->getId()]
            );
    }

    private function deleteIntakes(School $school): void
    {
        $tableIntake = $this->getTableName(Intake::class);
        $tableCourse = $this->getTableName(Course::class);
        $this->connection
            ->executeQuery(
                "DELETE FROM $tableIntake WHERE course_id IN (
                    SELECT id FROM $tableCourse WHERE school_id = :school_id
                 )",
                ['school_id' => $school->getId()]
            );
    }

    private function deleteCampuses(School $school): void
    {
        $entityClass = Campus::class;
        $this->em
            ->createQuery("DELETE FROM $entityClass e WHERE e.schoolId = :schoolId")
            ->setParameter('schoolId', $school->getId())
            ->execute();
    }

    private function deleteCourses(School $school): void
    {
        $entityClass = Course::class;
        $this->em
            ->createQuery("DELETE FROM $entityClass e WHERE e.schoolId = :schoolId")
            ->setParameter('schoolId', $school->getId())
            ->execute();
    }

    private function deleteStaffMembers(School $school): void
    {
        $entityClass = StaffMember::class;
        $this->em
            ->createQuery("DELETE FROM $entityClass e WHERE e.schoolId = :schoolId")
            ->setParameter('schoolId', $school->getId())
            ->execute();
    }

    private function getTableName(string $className): string
    {
        return $this->em->getClassMetadata($className)->getTableName();
    }
}
