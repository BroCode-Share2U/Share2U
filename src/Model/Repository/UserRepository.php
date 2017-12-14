<?php

namespace Model\Repository;


use Doctrine\ORM\EntityRepository;
use Model\User;

class UserRepository extends EntityRepository
{
    public function isUnique(User $user) {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select("")
            ->from()
            ->
    }
}