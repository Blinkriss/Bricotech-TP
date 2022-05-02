<?php

namespace App\Repository;

use App\Entity\ToolCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ToolCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToolCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToolCategory[]    findAll()
 * @method ToolCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToolCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToolCategory::class);
    }

    // /**
    //  * @return ToolCategory[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ToolCategory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
