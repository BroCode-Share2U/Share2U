<?php

namespace Model\Repository;

use Doctrine\ORM\EntityRepository;
use Model\User;

class UserRepository extends EntityRepository
{
    public function emailExists($email) {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select("u.id")
            ->from(User::class, "u")
            ->where("u.email = ?1")
            ->setParameter(1, $email);

        $result = $queryBuilder->getQuery()->getResult();

        return (count($result) > 0);
    }

    public function usernameExists($username) {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select("u.id")
            ->from(User::class, "u")
            ->where("u.username = ?1")
            ->setParameter(1, $username);

        $result = $queryBuilder->getQuery()->getResult();

        return (count($result) > 0);
    }

    public function userEmailExists(User $user) {
        return $this->emailExists($user->getEmail());
    }
}
