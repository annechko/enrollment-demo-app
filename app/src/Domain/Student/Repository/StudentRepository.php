<?php

declare(strict_types=1);

namespace App\Domain\Student\Repository;

use App\Domain\Core\NotFoundException;
use App\Domain\School\Entity\School\School;
use App\Domain\School\Entity\School\SchoolId;
use App\Domain\Student\Entity\Student\Student;
use App\Domain\Student\Entity\Student\StudentId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function save(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function get(StudentId $id): Student
    {
        $school = $this->find($id);
        if ($school === null) {
            throw new NotFoundException('Student not found.');
        }

        return $school;
    }
}
