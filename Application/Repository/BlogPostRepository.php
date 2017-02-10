<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 09/02/2017
 * Time: 22:48
 */
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class BlogPostRepository extends EntityRepository{

    public function delOnePost($id){

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
