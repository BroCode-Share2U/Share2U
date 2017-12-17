<?php

namespace Model\Repository;

use Doctrine\ORM\EntityRepository;
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
                    'l.confirmedAt',
                    'l.closedAt',
                    'i.id AS idItem',
                    'i.name',
                    'i.igdbId',
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
                'l.confirmedAt',
                'l.closedAt',
                'i.id AS idItem',
                'i.name',
                'i.igdbId',
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

    /**
     * @param Loan $loan
     * @param $status
     * @return array
     */
    public function patchLoanStatus(Loan $loan, $status)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->update(Loan::class, 'l')
            ->set('l.status', ':status')
                ->setParameter('status', $status)
            ->where('l = :loan')
                ->setParameter('loan', $loan)
        ;

        return $queryBuilder->getQuery()->execute();
    }
}
