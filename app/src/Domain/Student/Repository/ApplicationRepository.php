<?php

declare(strict_types=1);

namespace App\Domain\Student\Repository;

use App\Domain\Core\NotFoundException;
use App\Domain\Student\Entity\Application\Application;
use App\Domain\Student\Entity\Application\ApplicationId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Application>
 *
 * @method Application|null find($id, $lockMode = null, $lockVersion = null)
 * @method Application|null findOneBy(array $criteria, array $orderBy = null)
 * @method Application[]    findAll()
 * @method Application[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Application::class);
    }

    public function add(Application $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    public function remove(Application $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function get(ApplicationId $id): Application
    {
        $school = $this->find($id);
        if ($school === null) {
            throw new NotFoundException('Application not found.');
        }

        return $school;
    }
}
