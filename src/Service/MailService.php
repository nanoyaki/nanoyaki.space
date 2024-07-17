<?php

namespace App\Service;

use App\Entity\BlockedEmail;
use App\Entity\User;
use App\Exception\EmailBlockedException;
use App\Repository\BlockedEmailRepository;
use App\Repository\EmailConfirmationRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class MailService
{
    public function __construct(
        private MailerInterface             $mailer,
        private EmailConfirmationRepository $emailConfirmationRepository,
        private BlockedEmailRepository      $blockedEmailRepository,
        private UrlGeneratorInterface       $router
    ) {}

    /**
     * @throws TransportExceptionInterface
     * @throws EmailBlockedException
     */
    public function sendRegistrationConfirmationMail(User $user): void
    {
        $blockedEmail = $this->blockedEmailRepository->findOneByEmail($user->getEmail());
        if ($blockedEmail instanceof BlockedEmail) {
            throw new EmailBlockedException(blockedEmail: $blockedEmail);
        }

        $this->emailConfirmationRepository->save(
            $user->getEmailConfirmation()->regenerateToken()
        );

        $mail = (new TemplatedEmail())
            ->from(new Address('no-reply@nanoyaki.space', 'Nanoyaki.space'))
            ->to(new Address($user->getEmail(), $user->getUsername()))
            ->subject("Please confirm your email")
            ->htmlTemplate('email/confirm_email.html.twig')
            ->context([
                'token' => $user->getEmailConfirmation()->getToken()
            ]);

        $this->send($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(Email $mail): void
    {
        $unsubscribeUrl = $this->router->generate(
            'app_unsubscribe',
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL
        );

        $mail
            ->getHeaders()
            ->addTextHeader('List-Unsubscribe', '<' . $unsubscribeUrl . '>');

        $this->mailer->send($mail);
    }
}