<?php

declare(strict_types=1);

namespace App\Core\School\Repository;

use App\Core\Common\NotFoundException;
use App\Core\School\Entity\School\StaffMember;
use App\Core\School\Entity\School\StaffMemberId;
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

    public function getByInvitationToken(string $invitationToken): StaffMember
    {
        $entity = $this->findOneBy(['invitationToken.value' => $invitationToken]);
        if ($entity === null) {
            throw new NotFoundException('Staff Member not found.');
        }

        return $entity;
    }

    public function get(StaffMemberId $id): StaffMember
    {
        $member = $this->find($id);
        if ($member === null) {
            throw new NotFoundException('Staff member not found.');
        }

        return $member;
    }

    public function save(StaffMember $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function hasByEmail(string $adminEmail): bool
    {
        return $this->count(['email.value' => $adminEmail]) > 0;
    }
}
