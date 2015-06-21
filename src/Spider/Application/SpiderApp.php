<?php

namespace Paq\Spider\Application;


use Paq\FbParser\Application\Command\ParseFbDataCommand;
use Paq\FbParser\FacebookParser;
use Paq\Spider\Application\Command\CollectorStartCommand;
use Paq\Spider\Application\Command\StartCommand;
use Paq\Spider\Application\Command\TasksLoadCommand;
use Paq\Spider\Application\Command\WorkerHttpStartCommand;
use Paq\Spider\Application\Command\WorkerStartCommand;
use Paq\Spider\Application\Command\WorkerStartManyCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpiderApp extends Application
{
    const NAME = 'Scalable Web Spider (Crawler) by Damian Sromek';
    const VERSION = '1.0.0';

    /**
     * @var array
     */
    private $options;

    public function __construct(array $options)
    {
        parent::__construct(self::NAME, self::VERSION);

        $resolver = new OptionsResolver();
        $resolver->setRequired('worker_exec_command');

        $this->options = $resolver->resolve($options);

        $this->add(new StartCommand());
        $this->add(new TasksLoadCommand());
        $this->add(new CollectorStartCommand());
        $this->add(new WorkerHttpStartCommand());
        $this->add(new WorkerStartManyCommand($this->options['worker_exec_command']));
    }
}