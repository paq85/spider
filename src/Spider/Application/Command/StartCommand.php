<?php

namespace Paq\Spider\Application\Command;


use Paq\FbParser\FacebookParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class StartCommand extends Command
{

    protected function configure()
    {
        $this->setName('start')
            ->setDescription('Starts Task Loader, Collector and Workers to fetch URIs given on STDIN');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO: Implement
    }
}