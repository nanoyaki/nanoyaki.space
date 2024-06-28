<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    private const ROOT = 'app_blog_';
    public const POST = self::ROOT . 'post';

    #[Route('/blog/post/{id}', name: self::POST)]
    public function index(int $id, PostRepository $postRepository): Response
    {
        $post = $postRepository->getPostById($id);

        return $this->render('blog_post/index.html.twig', [
            'post' => $post
        ]);
    }
}
