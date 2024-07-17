<?php

namespace App\Repository;

use App\Entity\BlockedEmail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlockedEmail>
 */
class BlockedEmailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockedEmail::class);
    }

    public function findOneByEmail(string $email): ?BlockedEmail
    {
        $qb = $this->createQueryBuilder('be');

        $qb
            ->where('be.email = :email')
            ->setParameter('email', $email);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function save(BlockedEmail $blockedEmail): void
    {
        $em = $this->getEntityManager();

        $em->persist($blockedEmail);
        $em->flush();
    }
}
