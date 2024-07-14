<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

readonly class MailService
{
    public function __construct(
        private MailerInterface $mailer
    ) {}

    /**
     * @throws TransportExceptionInterface
     */
    public function sendRegistrationConfirmationMail(User $user): void
    {
        $mail = (new TemplatedEmail())
            ->from(new Address('no-reply@nanoyaki.space', 'Nanoyaki.space'))
            ->to(new Address($user->getEmail(), $user->getUsername()))
            ->subject("Please confirm your E-Mail")
            ->htmlTemplate('email/confirm_email.html.twig')
            ->context([
                'token' => $user->getEmailConfirmation()->getToken()
            ]);

        $this->mailer->send($mail);
    }
}