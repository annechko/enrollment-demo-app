<?php

declare(strict_types=1);

namespace App\ReadModel\Student;

use App\Domain\Student\Entity\Student\Student;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

// todo remove copy past
class StudentFetcher
{
    private Connection $connection;
    private string $tableName;

    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->tableName = $em->getClassMetadata(Student::class)->getTableName();
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
                'id',
                'email',
                'password_hash',
                'name',
                'surname',
                'is_email_verified',
                'roles',
            )
            ->from($this->tableName)
            ->where('email = :email')
            ->setParameter('email', $email)
            ->fetchAssociative();

        return $result;
    }
}
