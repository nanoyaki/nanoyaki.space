<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/blog/post', name: 'app_')]
class PostController extends AbstractController
{
    #[Route('/{id}', name: 'post')]
    public function index(
        Request $request,
        UrlGeneratorInterface $router,
        #[MapEntity(id: 'id')] Post $post
    ): Response
    {
        $previousPage = $request->headers->has('referer')
            ? $request->headers->get('referer')
            : $router->generate('app_index');

        return $this->render('blog_post/index.html.twig', [
            'post' => $post,
            'previousPage' => $previousPage
        ]);
    }
}
