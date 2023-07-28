<?php

declare(strict_types=1);

namespace App\ReadModel\Student;

use App\Domain\School\Entity\School\School;
use App\ReadModel\Student\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class SchoolFetcher
{
    private string $tableSchool = '';

    public function __construct(
        private readonly Connection $connection,
        private readonly PaginatorInterface $paginator,
        readonly EntityManagerInterface $em
    ) {
        $this->tableSchool = $em->getClassMetadata(School::class)->getTableName();
    }

    /**
     * @return PaginationInterface<int, mixed>
     */
    public function fetch(
        Filter $filter,
        int $page,
        int $size,
    ): PaginationInterface {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                's.id',
                's.name',
            )
            ->from($this->tableSchool, 's')
        ;

        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('LOWER(s.name)', ':name'));
            $qb->setParameter('name', '%' . mb_strtolower($filter->name) . '%');
        }

        $qb->andWhere('s.status = :status');
        $qb->setParameter('status', School::STATUS_ACTIVE);

        $qb->orderBy('LOWER(s.name)', 'asc');
        // todo remove pagination.

        return $this->paginator->paginate($qb, $page, $size);
    }
}
