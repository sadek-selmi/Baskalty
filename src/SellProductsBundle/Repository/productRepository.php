<?php

namespace SellProductsBundle\Repository;

/**
 * productRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class productRepository extends \Doctrine\ORM\EntityRepository
{
    public function findEntitiesByString($str)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM SellProductsBundle:product p
                WHERE p.name LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }


    public function findByUser ($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p.name,p.id,p.reference,p.price,p.image,p.quantite,p.description FROM SellProductsBundle:product p where p.user=:id'
            )
            ->setParameter('id',$id)
        ->getResult();
    }

}
