<?php

namespace Model\Repository;

use Doctrine\ORM\EntityRepository;
use Model\Item;

class ItemRepository extends EntityRepository {

    public function searchOthersItems($searchString, $user = null) {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $searchRegex = "%" . $searchString . "%";

        // only search for items not owned by the provided user
        if ($user) {
            $queryBuilder
                ->select("i")
                ->from(Item::class, "i")
                ->where("i.name LIKE ?1")
                ->setParameter(1, $searchRegex)
                ->andWhere("NOT i.owner = ?2")
                ->setParameter(2, $user->getId())
            ;
        }
        // search all items
        else {
            $queryBuilder
                ->select("i")
                ->from(Item::class, "i")
                ->where("i.name LIKE ?1")
                ->setParameter(1, $searchRegex)
            ;
        }

        $results = $queryBuilder->getQuery()->getResult();
        return $results;
    }
}