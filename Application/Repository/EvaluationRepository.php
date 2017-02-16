<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 15/02/2017
 * Time: 18:30
 */

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class EvaluationRepository extends EntityRepository{

    public function getEvaluationsForPost($id){

        $qb = $this->createQueryBuilder('e');

        $qb ->select('e')
            ->where('e.idPost = :id')
            ->setParameter('id', $id)
        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;

    }

}
