<?php

namespace App\Command;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'test:create-sample-post',
    description: 'A command to create a post intended for testing purposes',
)]
class TestCreateSamplePostCommand extends Command
{
    private const AUTHOR = 'author';
    private const TITLE = 'title';
    private const CONTENT = 'content';

    private const LOREM_IPSUM = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';

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
                substr(self::AUTHOR, 0, 1),
                InputOption::VALUE_OPTIONAL,
                'The username of the user to use a test author',
                'nanoyaki'
            )
            ->addOption(
                self::TITLE,
                substr(self::TITLE, 0, 1),
                InputOption::VALUE_OPTIONAL,
                'The title to use for the test post',
                'This is a test title'
            )
            ->addOption(
                self::CONTENT,
                substr(self::CONTENT, 0, 1),
                InputOption::VALUE_OPTIONAL,
                'The content and digest to use for the test post',
                self::LOREM_IPSUM
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

        $inputTitle = $input->getOption(self::TITLE);
        if (!is_string($inputTitle)) {
            $io->error('The title must be a string');
            return Command::FAILURE;
        }

        $inputContent = $input->getOption(self::CONTENT);
        if (!is_string($inputContent)) {
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
            $inputContent
        );

        $this->postRepository->save($post);

        return Command::SUCCESS;
    }
}
