<?php

namespace App\Console;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:add-user',
    description: 'Add new active user',
)]
class AddUserCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'User display name')
            ->addArgument('email', InputArgument::REQUIRED, 'User e-mail address')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addOption('admin', 'a', InputOption::VALUE_NONE, 'Give user admin permissions')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $command = new \App\Command\AddUserCommand();
        $command->uuid = Uuid::uuid4();
        $command->name = $input->getArgument('name');
        $command->email = $input->getArgument('email');
        $command->password = $input->getArgument('password');
        $command->roles = $input->getOption('admin') ? ['ROLE_ADMIN'] : [];
        $command->active = true;

        $this->messageBus->dispatch($command);

        $io->success('User added successfully.');

        return Command::SUCCESS;
    }
}
