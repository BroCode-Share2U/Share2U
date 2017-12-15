<?php

namespace Model\Repository;

use Doctrine\ORM\EntityRepository;
use Model\Item;
use Model\Loan;
use Model\User;

class LoanRepository extends EntityRepository
{
    public function getLoanIn($user, $status)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select(['l.id', 'l.status', 'i.name'])
            ->from(Loan::class, 'l')
            ->innerJoin('l.item','i')
            ->where('i.owner = :owner')
                ->setParameter('owner', $user)
           ->andWhere('l.status = :status')
                ->setParameter('status', $status)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    public function getLoanOut($user, $status)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select(['l.id', 'l.status', 'i.name'])
            ->from(Loan::class, 'l')
            ->innerJoin('l.item','i')
            ->where('l.borrower = :borrower')
                ->setParameter('borrower', $user)
           ->andWhere('l.status = :status')
                ->setParameter('status', $status)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

}
