<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('', name: 'app_')]
class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        PostRepository $postRepository
    ): Response
    {
        return $this->render('index/index.html.twig', [
            'posts' => $postRepository->getTenPosts(),
        ]);
    }
}
