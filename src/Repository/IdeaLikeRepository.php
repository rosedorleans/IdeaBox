<?php

namespace App\Repository;

use App\Entity\IdeaLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IdeaLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method IdeaLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method IdeaLike[]    findAll()
 * @method IdeaLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdeaLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IdeaLike::class);
    }

    // /**
    //  * @return IdeaLike[] Returns an array of IdeaLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IdeaLike
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
