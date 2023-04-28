<?php

declare(strict_types=1);

namespace App\Domain\School\Repository;

use App\Domain\Core\NotFoundException;
use App\Domain\School\Entity\Course\Course;
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

    public function get(string $id): Course
    {
        $course = $this->find($id);
        if ($course === null) {
            throw new NotFoundException('Course not found.');
        }

        return $course;
    }

    public function hasByName($name): bool
    {
        return $this->count(['name' => $name]) > 0;
    }

    public function findByName($name): ?Course
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * @return Course[]
     */
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('LOWER(c.name)')
            ->getQuery()
            ->execute();
    }
}
