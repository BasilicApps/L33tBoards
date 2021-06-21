<?php

namespace App\Repository;

use App\Entity\Board;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Board|null find($id, $lockMode = null, $lockVersion = null)
 * @method Board|null findOneBy(array $criteria, array $orderBy = null)
 * @method Board[]    findAll()
 * @method Board[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Board::class);
    }

    public function findLast($count)
    {
        return $this->createQueryBuilder('board')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Board[] Returns an array of Board objects
    //  */
    
    public function findByUrl($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.urlTitle = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findById($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.id = :val')
            ->setParameter('val', $value)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Board
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
