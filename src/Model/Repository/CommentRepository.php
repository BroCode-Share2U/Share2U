<?php

namespace Model\Repository;

use Doctrine\ORM\EntityRepository;
use Model\Comment;
use Model\User;

class CommentRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return string
     */
    public function getRatingUser(User $user)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select(['avg(c.rating) as avgRating',])
            ->from(Comment::class, 'c')
            ->innerJoin('c.user', 'u')
            ->where('c.user = :user')
                ->setParameter('user', $user)
        ;
        $result = $queryBuilder->getQuery()->getResult();
        return $result[0]['avgRating'];
    }

}
