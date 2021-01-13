<?php

namespace App\Command;

use App\Legacy\HtaccessGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateRedirectionsCommand extends Command
{
    protected static $defaultName = 'app:generate-redirections';

    private HtaccessGenerator $generator;

    public function __construct(HtaccessGenerator $generator)
    {
        $this->generator = $generator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate redirections rules for Nginx.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write($this->generator->generateNginxRewriteRules());

        return Command::SUCCESS;
    }
}
