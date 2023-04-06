<?php

declare(strict_types=1);

namespace App\ReadModel\Admin\School;

use App\ReadModel\Admin\School\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class SchoolFetcher
{
    public function __construct(
        private readonly Connection $connection,
        EntityManagerInterface $em,
        private readonly PaginatorInterface $paginator,
    ) {
    }

    public function fetch(
        Filter $filter,
        int $page,
        int $size,
        string $sort,
        string $direction
    ): PaginationInterface {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                's.id',
                's.admin_id',
                's.name',
                's.name',
                's.status',
                'm.email',
                'm.invitation_token_created_at AS invitation_date',
            )
            ->from('school_school', 's')
            ->innerJoin('s', 'school_staff_member', 'm', 's.admin_id = m.id');


        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('LOWER(s.name)', ':name'));
            $qb->setParameter('name', '%' . mb_strtolower($filter->name) . '%');
        }

        if ($filter->status) {
            $qb->andWhere('s.status = :status');
            $qb->setParameter('status', $filter->status);
        }

        $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($qb, $page, $size);
    }
}
