<?php

namespace Paq\Spider\Application\Command;


use Paq\FbParser\FacebookParser;
use Paq\Spider\Worker\WorkersManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class WorkerStartManyCommand extends Command
{

    private $workerExecCommand;

    public function __construct($workerExecCommand)
    {
        parent::__construct();
        $this->workerExecCommand = $workerExecCommand;
    }

    protected function configure()
    {
        $this->setName('worker:start-many')
            ->setDescription('Starts given amount of Workers')
            ->addOption('worker-count', 'c', InputOption::VALUE_REQUIRED, 'How many workers should be run?', 1)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $workerCount = (int) $input->getOption('worker-count');

        $manager = new WorkersManager($this->workerExecCommand);
        $manager->startWorkers($workerCount, $output);

        sleep(5);
    }
}