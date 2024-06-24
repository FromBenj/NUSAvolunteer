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
    public function findByVolunteerName(array $data): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.firstName like :name')
            ->orWhere('v.lastName like :name')
            ->setParameter('name', '%' . $data['name'] . '%')
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByWordsInText(array $data): ?array
    {
        $descriptionSearch = $data['description'];
        $descriptionWordsList = array_filter((explode(' ', $descriptionSearch)), function($word) {
            return $word !== '';
        });
        $query = $this->createQueryBuilder('v');
        foreach($descriptionWordsList as $word) {
            $query->orWhere('v.description LIKE :word')
                ->setParameter('word', '%' . $word . '%');
        }
        return $query->getQuery()
            ->getResult();
    }

    public function findByDisponibilities(array $data): ?array
    {
        $disponibilitySearched = $data['disponibilities'];
        $query = $this->createQueryBuilder('v');
        foreach($disponibilitySearched as $disponibility) {
            $query->orWhere(':disponibility IN (v.disponibilities)')
                ->setParameter('disponibility', "$disponibility");
        }
        return $query->getQuery()
            ->getResult();
    }
}
