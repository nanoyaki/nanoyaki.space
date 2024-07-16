<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DiscordController extends AbstractController
{
    #[Route('/.well-known/discord', name: 'app_discord_verification')]
    public function index(): Response
    {
        return new Response("dh=a90a54350f3a09336054e2f344a92c1bf81eb801");
    }
}
