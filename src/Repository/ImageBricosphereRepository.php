<?php

namespace App\Repository;

use App\Entity\ImageBricosphere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageBricosphere|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageBricosphere|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageBricosphere[]    findAll()
 * @method ImageBricosphere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageBricosphereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageBricosphere::class);
    }

    // /**
    //  * @return ImageBricosphere[] Returns an array of ImageBricosphere objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImageBricosphere
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
