<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\ImageRepository;
use App\Repository\UserRepository;
use App\Service\MailService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'test:send-email',
    description: 'Add a short description for your command',
)]
class TestSendEmailCommand extends Command
{
    private const RECEIVER = 'receiver';
    private const IS_USER = 'is-user';

    public function __construct(
        private readonly MailService $mailService,
        private readonly ValidatorInterface $validator,
        private readonly UserRepository $userRepository,
        private readonly ImageRepository $imageRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                self::IS_USER,
                'u',
                InputOption::VALUE_NONE,
                'Whether to send the mail to a registered user'
            )
            ->addArgument(self::RECEIVER, InputArgument::REQUIRED, 'The receiver\'s Email')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $receiverEmail = $input->getArgument(self::RECEIVER);
        if (!is_string($receiverEmail)) {
            $io->error('Input Email is not a string');
            return Command::FAILURE;
        }

        $violations = $this->validator->validate($receiverEmail, [ new Assert\Email() ]);
        if (count($violations) !== 0) {
            foreach ($violations as $violation) {
                $io->error($violation->getMessage());
            }
            return Command::FAILURE;
        }

        $user = $input->getOption(self::IS_USER)
            ? $this->userRepository->getUserByEmailAddress($receiverEmail)
            : new User('Test User', $receiverEmail, $this->imageRepository->getDefaultUserProfilePicture());
        if (!$user instanceof User) {
            $io->error('User for that Email was not found!');
            return Command::FAILURE;
        }

        try {
            $this->mailService->sendRegistrationConfirmationMail($user);
        } catch (TransportExceptionInterface $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
        $io->success('Test Email was sent!');

        return Command::SUCCESS;
    }
}
