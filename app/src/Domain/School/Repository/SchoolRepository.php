<?php

declare(strict_types=1);

namespace App\Domain\School\Repository;

use App\Domain\Core\NotFoundException;
use App\Domain\School\Entity\School\School;
use App\Domain\School\Entity\School\SchoolId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<School>
 *
 * @method School|null find($id, $lockMode = null, $lockVersion = null)
 * @method School|null findOneBy(array $criteria, array $orderBy = null)
 * @method School[]    findAll()
 * @method School[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, School::class);
    }

    public function add(School $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    public function remove(School $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
    }

    public function get(SchoolId $id): School
    {
        $school = $this->find($id);
        if ($school === null) {
            throw new NotFoundException('School not found.');
        }

        return $school;
    }
}
