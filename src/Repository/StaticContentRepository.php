<?php

namespace App\Repository;

use App\Entity\StaticContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StaticContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method StaticContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method StaticContent[]    findAll()
 * @method StaticContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaticContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StaticContent::class);
    }

    // /**
    //  * @return StaticContent[] Returns an array of StaticContent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StaticContent
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
