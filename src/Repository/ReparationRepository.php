<?php

namespace App\Repository;

use App\Entity\Reparation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
/**
 * @extends ServiceEntityRepository<Reparation>
 */
class ReparationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reparation::class);
    }




    public function findMaxIdByNumereqp($numeroqp)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.numeroEqp = :numeroqp')
            ->setParameter('numeroqp', $numeroqp)
            ->orderBy('m.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }




    public function joinWithEquipment()
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.numeroEqp', 'r', Join::WITH, 'u.numeroEqp = r.id')
            ->addSelect('r');
        
        return $qb->getQuery()->getResult();
    }
//    /**
//     * @return Reparation[] Returns an array of Reparation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reparation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
