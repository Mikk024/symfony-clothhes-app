<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findProductsByParams($gender, $type = null, $category = null, $brand = null): array
    {
        $qb =  $this->createQueryBuilder('p')
            ->select('p', 'c', 't', 'b')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.brand', 'b')
            ->leftJoin('c.type', 't')
            ->andWhere('p.gender = :gender')
            ->setParameter('gender', $gender)
            ;
            

        if ($category) {
            $qb
                ->andWhere('c.name = :category')
                ->setParameter('category', $category);
        }

        if ($brand) {
            $qb
                ->andWhere('b.name = :brand')
                ->setParameter('brand', $brand);
        }

        if ($type) {
            $qb
                ->andWhere('t.name = :type')
                ->setParameter('type', $type);
        }

        

        return $qb->getQuery()->getResult();
    }


//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
