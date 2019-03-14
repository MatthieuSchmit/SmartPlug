<?php

namespace App\Repository;

use App\Entity\PowerStrip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PowerStrip|null find($id, $lockMode = null, $lockVersion = null)
 * @method PowerStrip|null findOneBy(array $criteria, array $orderBy = null)
 * @method PowerStrip[]    findAll()
 * @method PowerStrip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PowerStripRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PowerStrip::class);
    }

    // /**
    //  * @return PowerStrip[] Returns an array of PowerStrip objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PowerStrip
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
