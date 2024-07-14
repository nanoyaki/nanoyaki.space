<?php

namespace App\Controller;

use App\Entity\RegisterData;
use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', 'app_')]
class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(): Response
    {
        return $this->render('login/index.html.twig');
    }

    #[Route('/register', name: 'register')]
    public function register(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $form = $this->createForm(RegisterType::class, new RegisterData());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var RegisterData $data */
            $data = $form->getData();

            if ($userRepository->userExists($data->getEmail(), $data->getUsername())) {
                return $this->render('login/register.html.twig', [
                    'registerForm' => $form
                ]);
            }

            $newUser = User::register($data, $passwordHasher);
            $userRepository->save($newUser);

            return $this->redirectToRoute('app_account_confirm_email');
        }

        return $this->render('login/register.html.twig', [
            'registerForm' => $form
        ]);
    }
}
