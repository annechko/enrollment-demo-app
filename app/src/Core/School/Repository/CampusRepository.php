<?php

declare(strict_types=1);

namespace App\Core\School\Repository;

use App\Core\Common\NotFoundException;
use App\Core\School\Entity\Campus\Campus;
use App\Core\School\Entity\Campus\CampusId;
use App\Core\School\Entity\School\SchoolId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Campus>
 *
 * @method Campus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campus[]    findAll()
 * @method Campus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campus::class);
    }

    public function add(Campus $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    public function remove(Campus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
    }

    public function get(CampusId $id): Campus
    {
        $campus = $this->find($id);
        if ($campus === null) {
            throw new NotFoundException('Campus not found.');
        }

        return $campus;
    }

    public function hasByName(string $name): bool
    {
        return $this->count(['name' => $name]) > 0;
    }

    public function findByName(string $name): ?Campus
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * @param array<integer> $campusIds
     *
     * @return Campus[]
     */
    public function findAllByIds(array $campusIds): array
    {
        return $this->findBy([
            'id' => $campusIds,
        ]);
    }

    /**
     * @return Campus[]
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
