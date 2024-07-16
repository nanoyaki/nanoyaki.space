<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/q', 'app_url_shortener_')]
class UrlShortenerController extends AbstractController
{
    #[Route('/routes', name: 'routes')]
    public function index()
    {
        // TODO: list all routes, with filters for global and user scoped routes
        //  add redirect to add / delete / edit
    }

    #[Route('/add-route', name: 'add_route')]
    public function addRoute()
    {
        // TODO: add form to add global route, restricted to admins / route managers
    }

    #[Route('/delete-route')]
    public function deleteRoute()
    {
        // TODO: handle deletion for routes, restricted to admins / route managers for global routes and restricted
        //  to users for their own routes
    }

    #[Route('/add-user-route')]
    public function addUserRoute()
    {
        // TODO: add form to add a route for a user, restricted to users
    }

    #[Route('/edit-route/{name}')]
    public function editRoute()
    {
        // TODO: add form to edit a route.
        //  an edited route will contain a history and leave a local storage key-pair with a checksum
        //  to inform users that have previously accessed that route that it has been changed
        //  -
        //  recently changed routes will *always* display that they were changed on the first time they were
        //  accessed within the first 14 days of that change
        //  -
        //  restricted to users for their own routes, and to admins / route managers for global routes
    }

    #[Route('/{shortenedUrl}')]
    public function handleGlobalRedirect(/** ShortenedUrl $shortenedUrl */)
    {
        // TODO: redirect global routes to the target page and, when necessary, inform the accessor of any changes to
        //  the route -> publicly accessible
    }

    #[Route('/{user:username}/{shortenedUrl:name}')]
    public function handleUserRedirect(
        /** User $user, */
        /** ShortenedUrl $shortenedUrl */
    )
    {
        // TODO: redirect user routes to the target page and, when necessary, inform the accessor of any changes to the
        //  route -> publicly accessible
    }
}
