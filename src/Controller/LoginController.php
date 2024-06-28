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

class LoginController extends AbstractController
{
    private const ROOT = 'app_';
    public const LOGIN = self::ROOT . 'login';
    public const REGISTER = self::ROOT . 'register';

    #[Route('/login', name: self::LOGIN)]
    public function index(): Response
    {
        return $this->render('login/index.html.twig');
    }

    #[Route('/register', name: self::REGISTER)]
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

            return $this->redirectToRoute(AccountController::CONFIRM_EMAIL);
        }

        return $this->render('login/register.html.twig', [
            'registerForm' => $form
        ]);
    }
}
