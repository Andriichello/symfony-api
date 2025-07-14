<?php

namespace App\Command;

use Exception;
use Psy\Configuration;
use Psy\Shell;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ShellCommand extends Command
{
    public function __construct(
        private readonly ContainerInterface $container,
        string $name = 'shell',
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Start PsySH (REPL).');
    }

    /**
     * Start PsySH (REPL).
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

        $config = new Configuration();
        $config->setStartupMessage('Use $container to access services.');

        $shell = new Shell($config);
        $shell->setScopeVariables(['container' => $this->container]);
        $shell->run();

        return Command::SUCCESS;
    }
}
