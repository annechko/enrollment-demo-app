<?php

declare(strict_types=1);

namespace App\Security;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractUserDbHelper
{
    private string $tableName;

    /**
     * @return array<string>
     */
    abstract protected function getUserTableColumns(): array;

    /**
     * @param array<string, mixed> $data
     *
     * @throws UserNotFoundException
     */
    abstract protected function mapDataToModel(array $data): UserInterface;

    public function __construct(
        private readonly Connection $connection,
        private readonly string $entityClassName,
        EntityManagerInterface $entityManager,
    ) {
        $this->tableName = $entityManager->getClassMetadata($this->entityClassName)->getTableName();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws UserNotFoundException
     */
    public function findByEmail(string $email): UserInterface
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                ...$this->getUserTableColumns()
            )
            ->from($this->tableName)
            ->where('email = :email')
            ->setParameter('email', $email)
            ->fetchAssociative();
        $data = $result === false ? [] : $result;
        if (count($data) === 0) {
            throw new UserNotFoundException();
        }

        return $this->mapDataToModel($data);
    }

    public function runUpgradePassword(
        string $userEmail,
        string $newHashedPassword,
    ): void {
        $affectedRowsNumber = $this->connection->createQueryBuilder()
            ->update($this->tableName, 'u')
            ->set('password_hash', ':newPassword')
            ->setParameter('newPassword', $newHashedPassword)
            ->where('u.email = :email')
            ->setParameter('email', $userEmail)
            ->executeStatement();
        if ($affectedRowsNumber !== 1) {
            throw new \DomainException("Updated $affectedRowsNumber rows during password upgrade.");
        }
    }
}
