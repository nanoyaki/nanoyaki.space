<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'test:delete-user',
    description: 'Add a short description for your command',
)]
class TestDeleteUserCommand extends Command
{
    private const USER_IDENTIFIER = 'identifier';

    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(self::USER_IDENTIFIER, InputArgument::REQUIRED, 'The user identifier of the user to delete.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $userIdentifier = $input->getArgument(self::USER_IDENTIFIER);
        if (!is_string($userIdentifier)) {
            $io->error('Please define a valid user.');
            return Command::FAILURE;
        }

        $user = $this->userRepository->getUserByIdentifier($userIdentifier);
        if ($user === null) {
            $io->error("User with identifier '$userIdentifier' does not exist.");
            return Command::FAILURE;
        }

        $username = $user->getUsername();
        $isConfirmed = $io->confirm("Are you sure you want to delete the user $username?", false);
        if (!$isConfirmed) {
            $io->info('Canceled.');
            return Command::SUCCESS;
        }

        $this->userRepository->delete($user);
        $io->success("Deleted user $username.");

        return Command::SUCCESS;
    }
}
