<?php

namespace App\Command;

use Exception;
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

    /**
     * Starts PsySH (REPL).
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($_ENV['APP_ENV'] !== 'dev') {
            $text = '<error>The `shell` command can only be run in dev environment.</error>';
            $output->writeln($text);

            return Command::FAILURE;
        }

        (new Shell())->run();

        return Command::SUCCESS;
    }
}
