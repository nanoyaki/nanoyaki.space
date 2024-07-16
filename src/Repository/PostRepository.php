<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
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
        $qb = $this->createQueryBuilder('p');

        if ($index !== null) {
            $qb
                ->where('p.id < :index')
                ->setParameter('index', $index);
        }

        $qb
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(10);

        return $qb->getQuery()->getResult();
    }

    public function getPostById(int $id): ?Post
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
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
