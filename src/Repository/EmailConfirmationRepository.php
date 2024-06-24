<?php

namespace App\Repository;

use App\Entity\EmailConfirmation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryProxy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepositoryProxy<EmailConfirmation>
 */
class EmailConfirmationRepository extends ServiceEntityRepositoryProxy
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailConfirmation::class);
    }

    public function save(EmailConfirmation $emailConfirmation): void
    {
        $em = $this->getEntityManager();

        $em->persist($emailConfirmation);
        $em->flush();
    }

    public function delete(EmailConfirmation $emailConfirmation): void
    {
        $em = $this->getEntityManager();

        $em->remove($emailConfirmation);
        $em->flush();
    }
}
