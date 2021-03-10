<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 4;
    public $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Car::class);
        $this->entityManager = $entityManager; 
    }

    public function getCarPaginator(int $offset, string $sort ) : Paginator
    {
        
        $property = "";
        $direction = 'DESC';
        
        if($sort == 'likes')
            $property = 'voteUp';
        else
            $property= "creationDate";

        /*$query = $this->entityManager->createQuery(
        "SELECT c.brand, c.model, c.capacity, c.owner, c.petrol, c.drivetrain, c.description, c.enginedescription, c.internaldescription, c.externaldescription,
         c.torque, (SELECT count(v.verdict) 
            FROM App\Entity\Vote v
            WHERE (v.car = :car) 
            AND (v.verdict = 'up')) as up,
             (SELECT count(v.verdict) 
            FROM App\Entity\Vote v
            WHERE (v.car = c) 
            AND (v.verdict = 'down')) as up 
        FROM App\Entity\Car c
        ")
        ->orderBy( 'c.' . $property, $direction)
        ->setMaxResults(self::PAGINATOR_PER_PAGE)
        ->setFirstResult($offset)
        ->getQuery()
        ;;
        //->setParameter('car', $row);
          */  

      /*  $queryCar = $this->entityMenager->createQuery(
            "SELECT p.slug, p.year, p.model, p.description, p.engineDescription, p.internalDescription, p.externalDescription, u.name
            FROM App\Entity\Car p
            LEFT JOIN p.owner  u   
            WHERE 
            (p.model = :key )
            OR( p.description LIKE :key  )
            OR( p.engineDescription LIKE :key  )
            OR( p.internalDescription LIKE :key )
            OR( p.externalDescription LIKE :key )    
            ORDER BY p.model ASC"
        )->setParameter('key', '%'.$key.'%');
        */
        $query = $this->createQueryBuilder('c')
        ->orderBy( 'c.' . $property, $direction)
        ->setMaxResults(self::PAGINATOR_PER_PAGE)
        ->setFirstResult($offset)
        ->getQuery()
        ;
        return new Paginator($query);
    }

    public function findAll()
    {
        return $this->findBy([], ['creationDate' => 'ASC', 'model' => 'ASC' ]);
    }
    // /**
    //  * @return Car[] Returns an array of Car objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Car
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
