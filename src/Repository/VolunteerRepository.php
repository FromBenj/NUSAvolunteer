<?php

namespace App\Repository;

use App\Entity\Volunteer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Volunteer>
 */
class VolunteerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Volunteer::class);
    }

    /**
         * @return Volunteer[] Returns an array of Volunteer objects
    */
    public function findByVolunteerName(?string $name): array
    {
        if ($name === null) {
            return [];
        }
        return $this->createQueryBuilder('v')
            ->andWhere('v.firstName like :name')
            ->orWhere('v.lastName like :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByWordInDescription(string $word): ?array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.description like :word')
            ->setParameter('word', '%' . $word . '%')
            ->getQuery()
            ->getResult();
    }
}
