<?php

namespace Model\Repository;

use Doctrine\ORM\EntityRepository;
use Model\Item;
use Model\Loan;
use Model\User;

class LoanRepository extends EntityRepository
{
    /**
     * @param User $user
     * @param $status
     * @return array
     */
    public function getLoansIn(User $user, $status)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select(
                [
                    'l.id as idLoan',
                    'l.status',
                    'l.requestMessage',
                    'l.requestedAt',
                    'i.id AS idItem',
                    'i.name',
                    'i.cover',
                    'u.firstname AS firstnameBorrower',
                    'u.lastname AS lastnameBorrower',
                    'u.username AS usernameBorrower',
                    'u.id AS idBorrower',
                ]
        )
            ->from(Loan::class, 'l')
            ->innerJoin('l.item','i')
            ->innerJoin('l.borrower', 'u')
            ->where('i.owner = :owner')
                ->setParameter('owner', $user)
            ->andWhere('l.status = :status')
                ->setParameter('status', $status)
            ->orderBy('l.requestedAt', 'DESC');
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @param $status
     * @return array
     */
    public function getLoansOut(User $user, $status)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select(
            [
                'l.id as idLoan',
                'l.status',
                'l.requestMessage',
                'l.requestedAt',
                'i.id AS idItem',
                'i.name',
                'i.cover',
                'u.firstname AS firstnameOwner',
                'u.lastname AS lastnameOwner',
                'u.username AS usernameOwner',
                'u.id AS idOwner'
            ]
        )
            ->from(Loan::class, 'l')
            ->innerJoin('l.item','i')
            ->innerJoin('i.owner', 'u')
            ->where('l.borrower = :borrower')
                ->setParameter('borrower', $user)
            ->andWhere('l.status = :status')
                ->setParameter('status', $status)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    public function itemIsLoaned(Item $item)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('l.id')
            ->from(Loan::class, 'l')
            ->innerJoin('l.item','i')
            ->where('i.id = :itemId')
                ->setParameter('itemId', $item->getId())
            ->andWhere('l.status = :status')
                ->setParameter('status', Loan::STATUS_IN_PROGRESS)
        ;

        $result = $queryBuilder->getQuery()->getArrayResult();
        return (count($result) > 0);
    }

    public function itemIsRequested(Item $item)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('l.id')
            ->from(Loan::class, 'l')
            ->innerJoin('l.item','i')
            ->where('i.id = :itemId')
                ->setParameter('itemId', $item->getId())
            ->andWhere('l.status = :status')
                ->setParameter('status', Loan::STATUS_REQUESTED)
        ;

        $result = $queryBuilder->getQuery()->getArrayResult();
        return (count($result) > 0);
    }

    public function isOwnerOfItem(User $user, Item $item) {
        return $item->getOwner()->getId() === $user->getId();
    }
}
