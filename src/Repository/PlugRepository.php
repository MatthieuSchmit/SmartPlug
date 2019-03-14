<?php

namespace App\Repository;

use App\Entity\Plug;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Plug|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plug|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plug[]    findAll()
 * @method Plug[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlugRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Plug::class);
    }

    // /**
    //  * @return Plug[] Returns an array of Plug objects
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
    public function findOneBySomeField($value): ?Plug
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
