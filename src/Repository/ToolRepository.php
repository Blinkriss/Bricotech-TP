<?php

namespace App\Repository;

use App\Entity\Tool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Statement;

/**
 * @method Tool|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tool|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tool[]    findAll()
 * @method Tool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tool::class);
    }

/*     public function findToolsInAllBricospheres($userId)
    {
        $sql = "SELECT distinct u2.id, t.name 
                    FROM user u 
                    JOIN user_bricosphere ub ON u.id = ub.user_id 
                    JOIN user_bricosphere ub2 ON ub2.bricosphere_id = ub.bricosphere_id 
                    JOIN user u2 ON ub2.user_id = u2.id 
                    JOIN tool t ON u2.id = t.user_id 
                    WHERE u.id =" . $userId;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->executeQuery();
    
        return $stmt->Result::fetchAllAssociative()
        ;
    } */


    /*
    public function findOneBySomeField($value): ?Tool
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
