<?php

namespace App\Command;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-sample-post',
    description: 'A command to create a post',
)]
class TestCreateSamplePostCommand extends Command
{
    private const AUTHOR = 'author';
    private const TITLE = 'title';
    private const DIGEST = 'digest';
    private const CONTENT = 'content';

    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly UserRepository $userRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                self::AUTHOR,
                'u',
                InputOption::VALUE_OPTIONAL,
                'The username of the user to use a test author',
                'nano'
            )
            ->addArgument(
                self::TITLE,
                InputArgument::REQUIRED,
                'The title to use for the test post'
            )
            ->addArgument(
                self::DIGEST,
                InputArgument::REQUIRED,
                'The digest to use for the post preview'
            )
            ->addArgument(
                self::CONTENT,
                InputArgument::REQUIRED,
                'The content to use for the test post'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $inputUsername = $input->getOption(self::AUTHOR);
        if (!is_string($inputUsername)) {
            $io->error('The username must be a string');
            return Command::FAILURE;
        }

        $inputTitle = $input->getArgument(self::TITLE);
        if (!is_string($inputTitle)) {
            $io->error('The title must be a string');
            return Command::FAILURE;
        }

        $inputContent = $input->getArgument(self::CONTENT);
        if (!is_string($inputContent)) {
            $io->error('The title must be a string');
            return Command::FAILURE;
        }

        $inputDigest = $input->getArgument(self::DIGEST);
        if (!is_string($inputDigest)) {
            $io->error('The title must be a string');
            return Command::FAILURE;
        }

        $testAuthor = $this->userRepository->getUserByUsername($inputUsername);
        if (!$testAuthor instanceof User) {
            $io->error('The user does not exist. Please try with another user.');
            return Command::FAILURE;
        }

        $post = new Post(
            $testAuthor,
            $inputTitle,
            $inputContent,
            $inputDigest,
            true
        );

        $this->postRepository->save($post);
        $io->success("Successfully created post with title {$post->getTitle()}");

        return Command::SUCCESS;
    }
}
