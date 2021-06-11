<?php

namespace App\Repository;

use App\Entity\ProductQuantity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductQuantity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductQuantity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductQuantity[]    findAll()
 * @method ProductQuantity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductQuantityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductQuantity::class);
    }

    // /**
    //  * @return ProductQuantity[] Returns an array of ProductQuantity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
    public function findOneByPanierIdAndProductId($panier, $product): ?ProductQuantity
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.Panier = :panier', 'p.Product = :product')
            ->setParameters(new ArrayCollection([
                new Parameter('panier', $panier),
                new Parameter('product', $product)
            ]))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
