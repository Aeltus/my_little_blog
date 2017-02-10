<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 10/02/2017
 * Time: 16:36
 */

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository{

    public function delOneComment($id){

        $qb = $this->createQueryBuilder('a');

        $qb ->delete()
            ->where('a.id = :id')
            ->setParameter('id', $id)
        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

}