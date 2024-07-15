<?php

namespace App\Controller;

use App\Entity\RegisterData;
use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use App\Service\MailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('', name: 'app_')]
class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $lastError = $authenticationUtils->getLastAuthenticationError();
        if ($lastError !== null) {
            $this->addFlash('error', $lastError->getMessage());
        }

        return $this->render('login/index.html.twig');
    }

    #[Route('/register', name: 'register')]
    public function register(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        MailService $mailService
    ): Response
    {
        $form = $this->createForm(RegisterType::class, new RegisterData());

        $errors = null;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            assert($data instanceof RegisterData);

            if ($userRepository->userExists($data->getEmail(), $data->getUsername())) {
                $this->addFlash('error', 'A user with that email or username already exists');
                return $this->render('login/register.html.twig', [
                    'registerForm' => $form
                ]);
            }

            $newUser = User::register($data, $passwordHasher);
            $userRepository->save($newUser);

            try {
                $mailService->sendRegistrationConfirmationMail($newUser);
            } catch (TransportExceptionInterface) {
                $this->addFlash(
                    'error',
                    'There was an error trying to send the confirmation mail. ' .
                    'Please try again later.'
                );
            }

            return $this->redirectToRoute('app_account_confirm_email');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $errors = $form->getErrors(true);
        }

        return $this->render('login/register.html.twig', [
            'registerForm' => $form,
            'errors' => $errors,
        ]);
    }
}
