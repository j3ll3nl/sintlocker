<?php

namespace App\Repository;

use App\Entity\Position;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Position>
 *
 * @method Position|null find($id, $lockMode = null, $lockVersion = null)
 * @method Position|null findOneBy(array $criteria, array $orderBy = null)
 * @method Position[]    findAll()
 * @method Position[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PositionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Position::class);
    }

//    /**
//     * @return Positions[] Returns an array of Positions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findOneByLockAndLockColumn(int $lock, string $lockColumn): ?Position
    {
        return $this->createQueryBuilder('p')
            ->where('p.lock = :lock')
            ->andWhere('p.lock_column = :lock_column')
            ->setParameter('lock', $lock)
            ->setParameter('lock_column', $lockColumn)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
