<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 09/02/2017
 * Time: 22:48
 */
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BlogPostRepository extends EntityRepository{

    public function delOnePost($id){

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

    public function getPosts($visible = -1, $limitStart = 0, $number = 10, $tag = NULL, $orderBy = 'lastUpdate', $order = 'DESC', $search=NULL){

        $qb = $this->createQueryBuilder('a');

        $qb ->select('a')
            ->addSelect('t')
            ->leftJoin('a.tags', 't')
            ->setFirstResult($limitStart)
            ->setMaxResults($number)
            ->orderBy('a.'.$orderBy, $order);
        ;

        if ($visible != '2'){
            $qb ->where('a.visible = :visible')
                ->setParameter('visible', $visible)
            ;
        }

        if (isset($tag)){

            if ($visible != '2'){

                $qb ->andWhere('t.id = :tag')
                    ->setParameter('tag', $tag)
                ;

            } else {

                $qb ->where('t.id = :tag')
                    ->setParameter('tag', $tag)
                ;
            }

        }

        if (isset($search)){

            if (isset($tag) || $visible != '2'){

                $qb ->andWhere('a.hook LIKE :search')
                    ->setParameter('search', '%'.$search.'%')
                ;

            } else {

                $qb ->where('a.hook like :search')
                    ->setParameter('search', '%'.$search.'%')
                ;

            }

        }

        return new Paginator($qb, true);

    }

}
