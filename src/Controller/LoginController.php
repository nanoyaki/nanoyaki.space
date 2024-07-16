<?php

namespace App\Controller;

use App\Entity\RegisterData;
use App\Exception\UserExistsException;
use App\Form\RegisterType;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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
        UserService $userService
    ): Response
    {
        $form = $this->createForm(RegisterType::class, new RegisterData());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            assert($data instanceof RegisterData);

            try {
                $userService->registerUser($data);
            } catch (UserExistsException) {
                $this->addFlash('error', 'A user with that email or username already exists');

                return $this->render('login/register.html.twig', [
                    'registerForm' => $form
                ]);
            } catch (TransportExceptionInterface) {
                $this->addFlash(
                    'error',
                    'There was an error trying to send the confirmation mail. ' .
                    'Please try again later.'
                );

                return $this->render('login/register.html.twig', [
                    'registerForm' => $form
                ]);
            }

            return $this->redirectToRoute('app_account_confirm_email');
        }

        return $this->render('login/register.html.twig', [
            'registerForm' => $form,
        ]);
    }
}
