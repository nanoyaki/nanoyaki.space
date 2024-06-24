<?php

namespace App\Controller;

use App\Entity\ConfirmEmailData;
use App\Entity\RegisterData;
use App\Entity\User;
use App\Form\ConfirmEmailType;
use App\Form\RegisterType;
use App\Repository\EmailConfirmationRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
        private readonly EmailConfirmationRepository $emailConfirmRepository,
    ) {}

    #[Route('/login', name: 'app_login')]
    public function index(): Response
    {
        return $this->render('login/index.html.twig');
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        $form = $this->createForm(RegisterType::class, new RegisterData());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var RegisterData $data */
            $data = $form->getData();

            if ($this->userRepository->userExists($data->getEmail(), $data->getUsername())) {
                return $this->render('login/register.html.twig', [
                    'registerForm' => $form
                ]);
            }

            $newUser = User::register($data, $this->passwordHasher);
            $newEmailConfirmation = $newUser->getEmailCofirmation();

            $this->emailConfirmRepository->save($newEmailConfirmation);
            $this->userRepository->save($newUser);

            return $this->redirectToRoute('app_account_confirm_email');
        }

        return $this->render('login/register.html.twig', [
            'registerForm' => $form
        ]);
    }

    #[Route('/account/confirm-email', name: 'app_account_confirm_email')]
    public function confirmEmail(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->getEmailCofirmation()->isConfirmed()) {
            return $this->redirectToRoute('app_index');
        }

        $form = $this->createForm(ConfirmEmailType::class, new ConfirmEmailData());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ConfirmEmailData $data */
            $data = $form->getData();

            $token = $data->getToken();
            $userEmailConfirmation = $user->getEmailCofirmation();

            if (!$userEmailConfirmation->tryVerification($token)->isConfirmed()) {
                $this->addFlash('warning', 'The code you entered is incorrect or has expired. Please send a new confirmation mail below.');
                return $this->render('login/confirmEmail.html.twig', [
                    'confirmEmailForm' => $form
                ]);
            }
            $this->emailConfirmRepository->save($userEmailConfirmation);

            $this->addFlash('success', 'Your email was verified successfully.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('login/confirmEmail.html.twig', [
            'confirmEmailForm' => $form
        ]);
    }
}
