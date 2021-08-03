<?php

namespace App\Repository;

use App\Entity\Tchat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tchat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tchat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tchat[]    findAll()
 * @method Tchat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tchat::class);
    }

    /**
     * @return Tchat[] Returns an array of Tchat objects
     */
    public function findTchatByChannel($channel)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.channel = :channel')
            ->setParameter('channel', $channel)
            ->setMaxResults(10)
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Tchat[] Returns an array of Tchat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tchat
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
