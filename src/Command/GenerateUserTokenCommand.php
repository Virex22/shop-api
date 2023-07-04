<?php

namespace App\Command;

use App\Entity\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'tool:generate-user-token',
    description: 'Generate a new user token',
)]
class GenerateUserTokenCommand extends Command
{
    public function __construct(private \App\Repository\UserRepository $userRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $rdmToken = bin2hex(random_bytes(32));
        $user = new User();
        $user->setToken($rdmToken);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('password');
        $this->userRepository->save($user, true);

        $io->success('New user token generated: ' . $rdmToken);

        return Command::SUCCESS;
    }
}
