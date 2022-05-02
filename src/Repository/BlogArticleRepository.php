<?php

namespace App\Repository;

use App\Entity\BlogArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogArticle[]    findAll()
 * @method BlogArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogArticle::class);
    }

    /**
     * @return BlogArticle Returns a blogarticle object
     */
    public function findOneWithRelations(?int $id = null)
    {
        return $this->createQueryBuilder('m')
                // On précise l'id du movie qu'on veut
                // d'abord on précise la condition WHERE puis on définit la valeur du paramètre :id
                // (comme avec PDO et ->prepare())
                ->where('m.id = :id')
                ->setParameter('id', $id)

                // Crée un objet de la classe Query
                // Cet objet est capable d'exécuter le DQL généré grace au QueryBuilder (QB)
                ->getQuery()
                // getResult() est une méthode de la classe Query qui exécute la requête et
                // nous fournit les résultats
                ->getSingleResult()
        ;

        // On peut faire exactement la même chose mais en plusieurs instructions, en mettant le QB dans une variable
        /*
        $qb = $this->createQueryBuilder('m');

        if ($id !== null) {
            $qb->where('m.id = :id')
            ->setParameter('id', $id);
        }

        $qb ->leftJoin('m.genres', 'g')
            ->addSelect('g')
            ->leftJoin('m.roles', 'r')
            ->addSelect('r')
            ->leftJoin('r.person', 'p')
            ->addSelect('p')
        ;

        return $qb->getQuery()->getSingleResult();
        */
    }
    // /**
    //  * @return BlogArticle[] Returns an array of BlogArticle objects
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
    public function findOneBySomeField($value): ?BlogArticle
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
