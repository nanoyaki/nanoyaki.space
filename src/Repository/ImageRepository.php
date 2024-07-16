<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @extends ServiceEntityRepository<Image>
 */
class ImageRepository extends ServiceEntityRepository
{
    private Image $defaultUserProfilePicture;

    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Image::class);
    }

    public function getDefaultUserProfilePicture(): Image
    {
        return $this->defaultUserProfilePicture;
    }

    #[Required]
    public function setDefaultUserProfilePicture(Image $defaultUserProfilePicture): void
    {
        $qb = $this->createQueryBuilder('i')
            ->where('i.path = :imagePath')
            ->setParameter('imagePath', $defaultUserProfilePicture->getPath());

        $result = $qb->getQuery()->getOneOrNullResult();

        if (!$result instanceof Image) {
            $this->save($defaultUserProfilePicture);
        }

        $this->defaultUserProfilePicture = $result instanceof Image ? $result : $defaultUserProfilePicture;
    }

    public function save(Image $image): void
    {
        $em = $this->getEntityManager();

        $em->persist($image);
        $em->flush();
    }

    public function delete(Image $image): void
    {
        $em = $this->getEntityManager();

        $em->remove($image);
        $em->flush();
    }
}
