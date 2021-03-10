<?php

namespace App\Repository;

use App\Entity\Drivetrain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Drivetrain|null find($id, $lockMode = null, $lockVersion = null)
 * @method Drivetrain|null findOneBy(array $criteria, array $orderBy = null)
 * @method Drivetrain[]    findAll()
 * @method Drivetrain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrivetrainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drivetrain::class);
    }

    // /**
    //  * @return Drivetrain[] Returns an array of Drivetrain objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Drivetrain
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
