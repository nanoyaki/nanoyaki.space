<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_')]
class IndexController extends AbstractController
{
    public function __construct(
        private readonly PostRepository $postRepository
    ) {}

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $user = $this->getUser();

        if ($user instanceof User) {
            $content = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.";

            $post = new Post(
                $user,
                "This is a test title",
                $content,
                $content
            );

            $post2 = clone $post;
            $post3 = clone $post;

            $post->setDigest('Meowsers~');

            $post2->togglePin();

            $posts = [ $post, $post2, $post3 ];

            foreach ($posts as $post) {
                $this->postRepository->save($post);
            }
        }

        return $this->render('index/index.html.twig', [
            'posts' => $this->postRepository->getTenPosts(),
        ]);
    }
}
