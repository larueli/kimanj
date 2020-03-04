<?php

namespace App\Repository;

use App\Entity\ResaNavette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ResaNavette|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResaNavette|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResaNavette[]    findAll()
 * @method ResaNavette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResaNavetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResaNavette::class);
    }

    // /**
    //  * @return ResaNavette[] Returns an array of ResaNavette objects
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
    public function findOneBySomeField($value): ?ResaNavette
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
