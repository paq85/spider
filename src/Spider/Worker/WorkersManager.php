<?php
/**
 * (c) Damian Sromek <damian.sromek@gmail.com>
 */

namespace Paq\Spider\Worker;


use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class WorkersManager
{
    private $workerExecCommand;

    public function __construct($workerExecCommand)
    {
        $this->workerExecCommand = $workerExecCommand;
    }

    public function startWorkers($workerCount, OutputInterface $output)
    {
        $output->writeln('Workers Manager is about to start '. $workerCount . ' workers');
        /* @var \Symfony\Component\Process\Process[] $workers */
        $workers = new \SplObjectStorage();

        for ($i = 1; $i <= $workerCount; ++$i) {
            $output->writeln('Starting worker nr: ' . $i . ' - ' . $this->workerExecCommand);
            $worker = new Process($this->workerExecCommand);
            // $worker->disableOutput();
            $workers->attach($worker);
            $worker->start();

            if ($worker->isRunning()) {
                $output->writeln('Worker started [PID = ' . $worker->getPid() . ']');
            } else {
                $output->writeln('<error>Worker start failure. ' . $worker->getErrorOutput() . '</error>');
            }
        }

        while (count($workers) > 0) {
            $output->writeln('Managing ' . count($workers) . ' worker(s) on ' . date('Y-m-d H:i:s'));
            $workersToRemove = [];
            foreach ($workers as $worker) {
                if (! $worker->isRunning()) {
                    $output->writeln('<info>Found non running worker.</info>');
                    $workersToRemove[] = $worker;
                }
            }

            foreach ($workersToRemove as $worker) {
                $workers->detach($worker);
            }

            sleep(1);
        }

        $output->writeln('Workers Manager exits.');
    }
}