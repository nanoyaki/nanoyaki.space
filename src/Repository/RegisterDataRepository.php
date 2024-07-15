<?php

namespace App\Repository;

use App\Entity\RegisterData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RegisterData>
 */
class RegisterDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegisterData::class);
    }

    public function save(RegisterData $registerData): void
    {
        $em = $this->getEntityManager();

        $em->persist($registerData);
        $em->flush();
    }

    public function delete(RegisterData $registerData): void
    {
        $em = $this->getEntityManager();

        $em->remove($registerData);
        $em->flush();
    }
}
