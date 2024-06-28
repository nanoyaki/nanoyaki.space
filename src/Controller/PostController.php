<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    private const ROOT = 'app_blog_';
    public const POST = self::ROOT . 'post';

    #[Route('/blog/post/{id}', name: self::POST)]
    public function index(int $id): Response
    {
        $post = $this->getUser()
            ->getPosts()
            ->filter(fn (Post $post) => $post->getId() === $id)
            ->first();

        return $this->render('blog_post/index.html.twig', [
            'post' => $post
        ]);
    }
}
