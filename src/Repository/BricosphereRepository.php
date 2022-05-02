<?php

namespace App\Repository;

use App\Entity\Bricosphere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bricosphere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bricosphere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bricosphere[]    findAll()
 * @method Bricosphere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BricosphereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bricosphere::class);
    }


    /**
     * @return Bricosphere Returns a Bricosphere object
     */
    public function findOneWithRelations(?int $id = null)
    {
        return $this->createQueryBuilder('b')

                ->where('b.id = :id')
                ->setParameter('id', $id)

                // On va chercher tous les users de la bricosphere
                ->leftJoin('b.users', 'u')
                ->addSelect('u')

                //On va chercher tous les tools des users
                /* ->leftJoin('u.tool', 't')
                ->addSelect('t') */

                // Crée un objet de la classe Query
                // Cet objet est capable d'exécuter le DQL généré grace au QueryBuilder (QB)
                ->getQuery()
                // getResult() est une méthode de la classe Query qui exécute la requête et
                // nous fournit les résultats
                // ->getSingleResult()
                ->getOneOrNullResult()
        ;

    }

    // /**
    //  * @return Bricosphere[] Returns an array of Bricosphere objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bricosphere
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
