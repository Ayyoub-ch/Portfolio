<?php

namespace App\Repository;

use App\Entity\Sejour;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sejour>
 */
class SejourRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sejour::class);
    }

    public function findStartTodayService(int $serviceId)
    {
        $qb = $this->createQueryBuilder('s')
            ->join('s.patient', 'p')->addSelect('p')
            ->join('s.chambre', 'c')->addSelect('c')
            ->where('s.service = :serviceId')
            ->andWhere('s.date_entree = :today')
            ->setParameter('serviceId', $serviceId)
            ->setParameter('today', new \DateTimeImmutable('today'))
            ->orderBy('p.nom', 'ASC');

        return $qb->getQuery()->getResult();
    }


    public function findByArriveeJour(\DateTimeInterface $date)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.date_entree = :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->orderBy('s.date_entree', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findBySortieJour(\DateTimeInterface $date)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.date_sortie = :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->orderBy('s.date_sortie', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    //    /**
    //     * @return Sejour[] Returns an array of Sejour objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Sejour
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}