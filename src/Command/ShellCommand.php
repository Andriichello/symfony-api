<?php

namespace App\Command;

use Psy\Shell;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShellCommand extends Command
{
    protected function configure(): void
    {
        parent::configure();

        $this->setName('shell');
        $this->setDescription('Interact with your application via PsySH (REPL).');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        (new Shell())->run();

        return Command::SUCCESS;
    }
}
