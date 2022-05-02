<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @return User Returns a User object
     */
    public function findOneWithRelations(?int $id = null)
    {
        return $this->createQueryBuilder('u')
                // On précise l'id du user qu'on veut
                // d'abord on précise la condition WHERE puis on définit la valeur du paramètre :id
                // (comme avec PDO et ->prepare())
                ->where('u.id = :id')
                ->setParameter('id', $id)

                // On va chercher tous les talents du user
                ->leftJoin('u.talents', 't')
                ->addSelect('t')

                // On va chercher tous les bricosphères du user
                /* ->leftJoin('u.bricosphere', 'b')
                ->addSelect('b') */

                // Crée un objet de la classe Query
                // Cet objet est capable d'exécuter le DQL généré grace au QueryBuilder (QB)
                ->getQuery()
                // getResult() est une méthode de la classe Query qui exécute la requête et
                // nous fournit les résultats
                ->getSingleResult()
                ;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */

    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
