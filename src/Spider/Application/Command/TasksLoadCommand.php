<?php

namespace Paq\Spider\Application\Command;


use Paq\FbParser\FacebookParser;
use Paq\Spider\TaskLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class TasksLoadCommand extends Command
{

    protected function configure()
    {
        $this->setName('tasks:load')
            ->setDescription('Read Tasks input data from STDIN');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loader = new TaskLoader();
        $loader->load(STDIN, $output);

        sleep(5); // so we don't miss the info
    }
}