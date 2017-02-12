<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 09/02/2017
 * Time: 19:12
 */
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository{

    public function delOneTag($id){

        $qb = $this->createQueryBuilder('a');

        $qb ->delete()
            ->where('a.id = :id')
            ->setParameter('id', $id)
        ;

        return $qb
            ->getQuery()
            ->execute()
        ;
    }

}
