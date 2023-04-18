<?php

declare(strict_types=1);

namespace App\Domain\School\Repository;

use App\Domain\Core\NotFoundException;
use App\Domain\School\Entity\School\StaffMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StaffMember>
 *
 * @method StaffMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method StaffMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method StaffMember[]    findAll()
 * @method StaffMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolStaffMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StaffMember::class);
    }

    public function findByEmail(string $email): ?StaffMember
    {
        return $this->findOneBy(['email.value' => $email]);
    }

    public function getByInvitationToken($invitationToken): StaffMember
    {
        $entity = $this->findOneBy(['invitationToken.value' => $invitationToken]);
        if ($entity === null) {
            throw new NotFoundException('Staff Member not found.');
        }

        return $entity;
    }
}
