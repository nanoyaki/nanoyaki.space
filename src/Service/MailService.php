<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailService
{
    public function __construct(
        private readonly MailerInterface $mailer
    ) {}

    /**
     * TODO: store mails in the db so that failed mails get sent later
     *
     * @throws TransportExceptionInterface
     */
    public function sendRegistrationConfirmationMail(User $user): void
    {
        $userEmail = $user->getEmail();
        $username = $user->getUsername();

        $confirmationCode = $user->getEmailCofirmation()->getToken();

        $mail = (new TemplatedEmail())
            ->to(new Address($userEmail, $username))
            ->subject("Please confirm your E-Mail.")
            ->htmlTemplate('email/confirmEmail.html.twig')
            ->context([
                'token' => $confirmationCode
            ]);

        $this->mailer->send($mail);
    }
}