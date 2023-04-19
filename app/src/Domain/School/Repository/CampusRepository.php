<?php

declare(strict_types=1);

namespace App\Domain\School\Repository;

use App\Domain\Core\NotFoundException;
use App\Domain\School\Entity\Campus\Campus;
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

    public function get(string $id): Campus
    {
        $Campus = $this->find($id);
        if ($Campus === null) {
            throw new NotFoundException('Campus not found.');
        }

        return $Campus;
    }

    public function hasByName($name): bool
    {
        return $this->count(['name' => $name]) > 0;
    }
}