<?php

namespace App\Service;

use App\Entity\RegisterData;
use App\Entity\User;
use App\Exception\EmailAlreadyConfirmedException;
use App\Exception\EmailConfirmationInvalidTokenException;
use App\Exception\UserExistsException;
use App\Repository\EmailConfirmationRepository;
use App\Repository\ImageRepository;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserService
{
    public function __construct(
        private UserRepository              $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private MailService                 $mailService,
        private EmailConfirmationRepository $emailConfirmationRepository,
        private ImageRepository             $imageRepository
    ) {}

    /**
     * @throws UserExistsException
     * @throws TransportExceptionInterface
     */
    public function registerUser(RegisterData $data): void
    {
        $existingUser = $this->userRepository->getUserByEmailAndUsername($data->getEmail(), $data->getUsername());
        if ($existingUser instanceof User) {
            throw new UserExistsException(user: $existingUser);
        }

        $defaultProfilePicture = $this->imageRepository->getDefaultUserProfilePicture();
        $newUser = User::register($data, $this->passwordHasher, $defaultProfilePicture);
        $this->userRepository->save($newUser);

        $this->mailService->sendRegistrationConfirmationMail($newUser);
    }

    /**
     * @throws EmailAlreadyConfirmedException
     * @throws EmailConfirmationInvalidTokenException
     */
    public function confirmEmailForUser(User $user, string $token): void
    {
        $userEmailConfirmation = $user->getEmailConfirmation();

        if ($userEmailConfirmation->isConfirmed()) {
            throw new EmailAlreadyConfirmedException(user: $user);
        }

        if (!$userEmailConfirmation->tryVerification($token)) {
            throw new EmailConfirmationInvalidTokenException(user: $user);
        }

        $this->emailConfirmationRepository->save($userEmailConfirmation);
    }
}