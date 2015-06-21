<?php

namespace Paq\Spider\Application\Command;


use Paq\Spider\TaskResultCollector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class CollectorStartCommand extends Command
{

    protected function configure()
    {
        $this->setName('collector:start')
            ->setDescription('Starts Results Collector')
            ->addOption('target-folder', 't', InputOption::VALUE_REQUIRED, 'Folder where Results should be stored in')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $resultsTargetPath = $input->getOption('target-folder');

        $collector = new TaskResultCollector($resultsTargetPath);
        $collector->collect($output);

        sleep(5); // so we don't miss the info
    }
}