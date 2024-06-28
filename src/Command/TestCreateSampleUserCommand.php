<?php

namespace App\Command;

use App\Entity\RegisterData;
use App\Entity\User;
use App\Enums\Role;
use App\Repository\EmailConfirmationRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'test:create-sample-user',
    description: 'A command to create a user intended for testing purposes',
)]
class TestCreateSampleUserCommand extends Command
{
    private const USERNAME = 'username';
    private const EMAIL = 'email';
    private const PASSWORD = 'password';
    private const ADMIN = 'is-admin';

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EmailConfirmationRepository $emailConfirmationRepository,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                self::USERNAME,
                substr(self::USERNAME, 0, 1),
                InputOption::VALUE_REQUIRED,
                'The user\'s username'
            )
            ->addOption(
                self::EMAIL,
                null,
                InputOption::VALUE_REQUIRED,
                'The user\'s email'
            )
            ->addOption(
                self::PASSWORD,
                substr(self::PASSWORD, 0, 1),
                InputOption::VALUE_REQUIRED,
                'The user\'s password in plain text'
            )
            ->addOption(
                self::ADMIN,
                'a',
                InputOption::VALUE_OPTIONAL,
                'Whether to register the user as an admin',
                false
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getOption(self::USERNAME);
        $email = $input->getOption(self::EMAIL);
        $password = $input->getOption(self::PASSWORD);
        if (!is_string($username) || !is_string($email) || !is_string($password)) {
            $io->error('The username, email, and password must be strings');
            return Command::FAILURE;
        }

        if ($this->userRepository->userExists($email, $username)) {
            $io->error('That user already exists!');
            return Command::FAILURE;
        }

        $isAdmin = $input->getOption(self::ADMIN);

        $registerData = (new RegisterData())
            ->setUsername($username)
            ->setPassword($password)
            ->setEmail($email);

        $user = User::register(
            $registerData,
            $this->passwordHasher,
            $isAdmin ? [ Role::Admin ] : []
        );

        $emailConfirmation = $user->getEmailCofirmation();
        $verificationToken = $emailConfirmation->getToken();
        if (!$emailConfirmation->tryVerification($verificationToken)) {
            $io->error('Something went horribly wrong');
            return Command::FAILURE;
        }
        $this->emailConfirmationRepository->save($emailConfirmation);

        $this->userRepository->save($user);

        return Command::SUCCESS;
    }
}
