<?php

namespace App\Repository;

use App\Entity\UserBoardVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserBoardVote|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBoardVote|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBoardVote[]    findAll()
 * @method UserBoardVote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBoardVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBoardVote::class);
    }

    public function findByUserAndBoard($userValue, $boardValue)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.user = :uval')
            ->setParameter('uval', $userValue)
            ->andWhere('v.board = :bval')
            ->setParameter('bval', $boardValue)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return UserBoardVote[] Returns an array of UserBoardVote objects
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
    public function findOneBySomeField($value): ?UserBoardVote
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
