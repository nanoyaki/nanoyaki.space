<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryProxy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepositoryProxy<Post>
 */
class PostRepository extends ServiceEntityRepositoryProxy
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param ?int $index
     * @return array<Post>
     */
    public function getTenPosts(?int $index = null): array
    {
        if ($index !== null) {
            $qb = $this->createQueryBuilder('p')
                ->where('p.id < :index')
                ->setParameter('index', $index)
                ->setMaxResults(10);
        } else {
            $qb = $this->createQueryBuilder('p')
                ->setMaxResults(10);
        }

        return $qb->getQuery()->getResult();
    }

    public function save(Post $post): void
    {
        $em = $this->getEntityManager();

        $em->persist($post);
        $em->flush();
    }

    public function delete(Post $post): void
    {
        $em = $this->getEntityManager();

        $em->remove($post);
        $em->flush();
    }
}
