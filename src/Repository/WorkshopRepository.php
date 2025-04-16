<?php

namespace App\Repository;

use App\Entity\Workshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workshop>
 */
class WorkshopRepository extends ServiceEntityRepository
{

    public function findByCategoryId(int $categoryId): array
    {
        return $this->createQueryBuilder('w')
            ->join('w.categories', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $categoryId)
            ->getQuery()
            ->getResult();
    }
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workshop::class);
    }

    public function findAllWithUsers(): array
{
    return $this->createQueryBuilder('w')
        ->leftJoin('w.users', 'u')
        ->addSelect('u')
        ->getQuery()
        ->getResult();
}

    //    /**
    //     * @return Workshop[] Returns an array of Workshop objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Workshop
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
