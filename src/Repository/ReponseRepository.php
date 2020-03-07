<?php

namespace App\Repository;

use App\Entity\Reponse;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Reponse|null find( $id, $lockMode = NULL, $lockVersion = NULL )
 * @method Reponse|null findOneBy( array $criteria, array $orderBy = NULL )
 * @method Reponse[]    findAll()
 * @method Reponse[]    findBy( array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL )
 */
class ReponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponse::class);
    }

    // /**
    //  * @return Reponse[] Returns an array of Reponse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reponse
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
