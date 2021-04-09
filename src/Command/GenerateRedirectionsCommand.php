<?php

declare(strict_types=1);

namespace App\Command;

use App\Legacy\HtaccessGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateRedirectionsCommand extends Command
{
    protected static $defaultName = 'app:generate-redirections';

    private HtaccessGenerator $generator;

    public function __construct(HtaccessGenerator $generator)
    {
        $this->generator = $generator;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generate redirections rules for Nginx.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln($this->generator->generateNginxRewriteRules());

        return Command::SUCCESS;
    }
}
