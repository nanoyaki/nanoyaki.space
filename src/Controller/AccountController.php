<?php

namespace App\Controller;

use App\Entity\ConfirmEmailData;
use App\Entity\User;
use App\Form\ConfirmEmailType;
use App\Repository\EmailConfirmationRepository;
use App\Service\MailService;
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
    public function confirmEmail(Request $request, EmailConfirmationRepository $emailConfirmRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->getEmailConfirmation()->isConfirmed()) {
            $this->addFlash('warning', 'You have already confirmed your email!');
            return $this->redirectToRoute('app_index');
        }

        $token = $request->query->has('code') ? $request->query->getString('code') : '';

        $form = $this->createForm(ConfirmEmailType::class, new ConfirmEmailData());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            assert($data instanceof ConfirmEmailData);

            $token = $data->getToken();
        }

        if (!$form->isSubmitted()) {
            return $this->render('login/confirm_email.html.twig', [
                'confirmEmailForm' => $form
            ]);
        }

        $userEmailConfirmation = $user->getEmailConfirmation();
        if ($userEmailConfirmation->tryVerification($token)) {
            $emailConfirmRepository->save($userEmailConfirmation);

            $this->addFlash('success', 'Your email was confirmed successfully.');
            return $this->redirectToRoute('app_index');
        }

        $this->addFlash(
            'error',
            'The confirmation code you entered is incorrect or has expired. Please send a new confirmation mail below.'
        );
        return $this->render('login/confirm_email.html.twig', [
            'confirmEmailForm' => $form
        ]);
    }

    #[Route('/resend-confirmation-mail', 'resend_confirmation_email')]
    public function resendConfirmationMail(
        MailService $mailService,
        EmailConfirmationRepository $emailConfirmationRepository
    ): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $emailConfirmationRepository->save(
            $user->getEmailConfirmation()->regenerateToken()
        );

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
