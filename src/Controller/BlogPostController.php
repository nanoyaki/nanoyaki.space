<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogPostController extends AbstractController
{
    #[Route('/blog/post/{id}', name: 'app_blog_post')]
    public function index(int $id): Response
    {
        return $this->render('blog_post/index.html.twig', [
            'controller_name' => 'BlogPostController',
        ]);
    }
}
