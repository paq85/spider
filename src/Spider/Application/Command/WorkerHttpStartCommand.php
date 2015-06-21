<?php

namespace Paq\Spider\Application\Command;


use Paq\FbParser\FacebookParser;
use Paq\Spider\Worker\HttpWorker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class WorkerHttpStartCommand extends Command
{

    protected function configure()
    {
        $this->setName('worker:http:start')
            ->setDescription('Starts Worker gathering data via HTTP protocol');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $worker = new HttpWorker();
        $worker->run($output);

        sleep(5); // so we don't miss the info
    }
}