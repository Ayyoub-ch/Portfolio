<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    public function findById($id){
        $em = $this->getEntityManager();

        $query = $em->createQueryBuilder()
            ->select('p')
            ->from('App\Entity\Patient', 'p')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        return $query->getQuery()->getResult();
    }

}

