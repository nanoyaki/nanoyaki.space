<?php

namespace App\Controller;

use App\Entity\ConfirmEmailData;
use App\Entity\User;
use App\Exception\EmailAlreadyConfirmedException;
use App\Exception\EmailConfirmationInvalidTokenException;
use App\Form\ConfirmEmailType;
use App\Service\MailService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/account', name: 'app_account_')]
class AccountController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function account(): Response
    {
        return $this->redirectToRoute('app_index');
    }

    #[Route('/confirm-email', name: 'confirm_email')]
    public function confirmEmail(Request $request, UserService $userService): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $token = $request->query->has('code') ? $request->query->getString('code') : null;

        $form = $this->createForm(ConfirmEmailType::class, new ConfirmEmailData());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            assert($data instanceof ConfirmEmailData);

            $token = $data->getToken();
        }

        if ($token === null) {
            return $this->render('login/confirm_email.html.twig', [
                'confirmEmailForm' => $form
            ]);
        }

        try {
            $userService->confirmEmailForUser($user, $token);
        } catch (EmailAlreadyConfirmedException) {
            $this->addFlash('warning', 'You have already confirmed your email!');
            return $this->redirectToRoute('app_index');
        } catch (EmailConfirmationInvalidTokenException) {
            $this->addFlash(
                'error',
                'The confirmation code you entered is incorrect or has expired. Please send a new confirmation mail below.'
            );
            return $this->render('login/confirm_email.html.twig', [
                'confirmEmailForm' => $form
            ]);
        }

        $this->addFlash('success', 'Your email was confirmed successfully.');
        return $this->redirectToRoute('app_index');
    }

    #[Route('/resend-confirmation-mail', 'resend_confirmation_email')]
    public function resendConfirmationMail(
        MailService $mailService
    ): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        try {
            $mailService->sendRegistrationConfirmationMail($user);

            $this->addFlash('success', 'Confirmation mail sent!');
        } catch (TransportExceptionInterface) {
            $this->addFlash(
                'error',
                'There was an error trying to send the confirmation mail. ' .
                'Please try again later.'
            );
        }

        return $this->redirectToRoute('app_account_confirm_email');
    }
}
