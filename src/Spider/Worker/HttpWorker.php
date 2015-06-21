<?php
/**
 * (c) Damian Sromek <damian.sromek@gmail.com>
 */

namespace Paq\Spider\Worker;


use Paq\Spider\Spider;
use Symfony\Component\Console\Output\OutputInterface;

class HttpWorker
{

    public function run(OutputInterface $output)
    {
        $context = new \ZMQContext();

        $tasksQueue = new \ZMQSocket($context, \ZMQ::SOCKET_PULL);
        $tasksQueue->connect(Spider::ZMQ_TASKS_QUEUE_DSN);

        $resultsQueue = new \ZMQSocket($context, \ZMQ::SOCKET_PUSH);
        $resultsQueue->connect(Spider::ZMQ_RESULTS_QUEUE_DSN);

        $output->writeln('HTTP Worker is waiting for tasks');
        while (true) {
            $string = $tasksQueue->recv();

            if ($string === Spider::ZMQ_COMMAND_BATCH_START) {
                $resultsQueue->send($string);
            } elseif (stripos($string, Spider::ZMQ_COMMAND_BATCH_END) !== false) {
                // send info for result collector how many results it should expect
                $resultsQueue->send($string);
            } elseif ($string === Spider::ZMQ_COMMAND_WORKER_QUIT) {
                $output->writeln('No more work. Worker stops now.');
                break; // no more work
            } else {
                $output->writeln('Fetching data from URI: ' . $string);
                $userData = file_get_contents($string); // TODO: use Guzzle
                $output->writeln('Sending result for URI: ' . $string);
                $resultsQueue->send($userData);
            }
        }
    }
}