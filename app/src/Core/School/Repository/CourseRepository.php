<?php

declare(strict_types=1);

namespace App\Core\School\Repository;

use App\Core\Common\NotFoundException;
use App\Core\School\Entity\Course\Course;
use App\Core\School\Entity\Course\CourseId;
use App\Core\School\Entity\School\SchoolId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Course>
 *
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function add(Course $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    public function remove(Course $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
    }

    public function get(CourseId $id): Course
    {
        $course = $this->find($id);
        if ($course === null) {
            throw new NotFoundException('Course not found.');
        }

        return $course;
    }

    /**
     * @return Course[]
     */
    public function findAllOrderedByName(SchoolId $schoolId): array
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->andWhere('c.schoolId = :schoolId')
            ->setParameter('schoolId', $schoolId)
            ->orderBy('LOWER(c.name)')
            ->getQuery()
            ->execute();
    }
}
