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

#[Route('/account')]
class AccountController extends AbstractController
{
    private const ROOT = 'app_account_';
    public const DASHBOARD = self::ROOT . 'dashboard';
    public const CONFIRM_EMAIL = self::ROOT . 'confirm_email';
    public const RESEND_CONFIRMATION_EMAIL = self::ROOT . 'resend_confirmation_email';

    #[Route('/dashboard', name: self::DASHBOARD)]
    public function account(): Response
    {
        return $this->redirectToRoute(IndexController::INDEX);
    }

    #[Route('/confirm-email', name: self::CONFIRM_EMAIL)]
    public function confirmEmail(Request $request, EmailConfirmationRepository $emailConfirmRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute(LoginController::LOGIN);
        }

        if ($user->getEmailCofirmation()->isConfirmed()) {
            return $this->redirectToRoute(IndexController::INDEX);
        }

        $token = $request->query->has('code') ? $request->query->getString('code') : null;

        $form = $this->createForm(ConfirmEmailType::class, new ConfirmEmailData());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ConfirmEmailData $data */
            $data = $form->getData();
            $token = $data->getToken();
        }

        if (is_null($token)) {
            return $this->render('login/confirmEmail.html.twig', [
                'confirmEmailForm' => $form
            ]);
        }

        $userEmailConfirmation = $user->getEmailCofirmation();
        if ($userEmailConfirmation->tryVerification($token)) {
            $emailConfirmRepository->save($userEmailConfirmation);

            $this->addFlash('success', 'Your email was verified successfully.');
            return $this->redirectToRoute(IndexController::INDEX);
        }

        $this->addFlash(
            'warning',
            'The code you entered is incorrect or has expired. Please send a new confirmation mail below.'
        );
        return $this->render('login/confirmEmail.html.twig', [
            'confirmEmailForm' => $form
        ]);
    }

    #[Route('/resend-confirmation-mail', self::RESEND_CONFIRMATION_EMAIL)]
    public function resendConfirmationMail(
        MailService $mailService,
        EmailConfirmationRepository $emailConfirmationRepository
    ): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute(LoginController::LOGIN);
        }

        $emailConfirmationRepository->save(
            $user->getEmailCofirmation()->regenerateToken()
        );

        try {
            $mailService->sendRegistrationConfirmationMail($user);

            $this->addFlash('success', 'Confirmation mail sent!');
        } catch (TransportExceptionInterface) {
            $this->addFlash(
                'warning',
                'There was an error trying to send the confirmation mail. ' .
                'Please try again later.'
            );
        }

        return $this->redirectToRoute(self::CONFIRM_EMAIL);
    }
}
