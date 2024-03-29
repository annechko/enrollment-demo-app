<?php

declare(strict_types=1);

namespace App\ReadModel\School;

use App\Core\School\Entity\School\StaffMember;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class SchoolStaffMemberFetcher
{
    private Connection $connection;
    private string $adminUserTableName;

    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->adminUserTableName = $em->getClassMetadata(StaffMember::class)->getTableName();
    }

    /**
     * @return array<string, mixed>
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function findByEmail(string $email): array
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                'school_id',
                'id',
                'email',
                'password_hash',
                'name',
                'surname',
                'roles',
            )
            ->from($this->adminUserTableName)
            ->where('email = :email')
            ->setParameter('email', $email)
            ->fetchAssociative();

        return $result;
    }
}
