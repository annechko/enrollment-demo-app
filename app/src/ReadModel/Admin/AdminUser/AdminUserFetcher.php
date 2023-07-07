<?php

declare(strict_types=1);

namespace App\ReadModel\Admin\AdminUser;

use App\Domain\Admin\Entity\AdminUser\AdminUser;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class AdminUserFetcher
{
    private Connection $connection;
    private string $adminUserTableName;

    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->adminUserTableName = $em->getClassMetadata(AdminUser::class)->getTableName();
    }

    public function findByEmail(string $email): ?AdminUserReadModel
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
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

        if (count($result) === 0) {
            return null;
        }

        return new AdminUserReadModel(
            $result['id'],
            $result['email'],
            $result['password_hash'],
            $result['name'],
            $result['surname'],
            $result['roles'],
        );
    }
}
