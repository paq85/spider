<?php
/**
 * (c) Damian Sromek <damian.sromek@gmail.com>
 */

namespace Paq\Spider;


use Symfony\Component\Console\Output\OutputInterface;

class TaskLoader
{

    public function load($inputStream, OutputInterface $output)
    {
        $context = new \ZMQContext();

        $tasksQueue = new \ZMQSocket($context, \ZMQ::SOCKET_PUSH);
        $tasksQueue->bind(Spider::ZMQ_TASKS_QUEUE_BIND_DSN);

        $statusQueue = new \ZMQSocket($context, \ZMQ::SOCKET_PULL);
        $statusQueue->connect(Spider::ZMQ_STATUS_QUEUE_DSN);

        /*
         * http://zguide.zeromq.org/php:all#advanced-request-reply
         * We have to synchronize the start of the batch with all workers being up and running.
         * This is a fairly common gotcha in ZeroMQ and there is no easy solution.
         * The zmq_connect method takes a certain time.
         * So when a set of workers connect to the ventilator, the first one to successfully connect will get a whole load of messages
         * in that short time while the others are also connecting.
         * If you don't synchronize the start of the batch somehow, the system won't run in parallel at all.
         * Try removing the wait in the ventilator, and see what happens.
         */
        $output->writeln('Giving workers some time to connect');
        sleep(3);
        //  The first message is "BATCH_START%" and signals start of batch
        $tasksQueue->send(Spider::ZMQ_COMMAND_BATCH_START);

        $taskCount = 0;
        while (($task = fgets($inputStream)) !== false) {
            $task = trim(preg_replace('/\s\s+/', ' ', $task));
            $tasksQueue->send($task);
            ++$taskCount;
        }

        $tasksQueue->send(Spider::ZMQ_COMMAND_BATCH_END . $taskCount); // send info for result collector how many results it should expect

        $output->writeln("<info>Total count of Tasks put in the Queue: $taskCount</info>");
        sleep (1);              //  Give 0MQ time to deliver

        $output->writeln('Waiting for acknowledgement from Task Result Collector');
        $output->writeln('Info from Task Result Collector: ' . $statusQueue->recv());

        $output->writeln('<info>Informing all workers to stop</info>');
        for ($i = 0; $i < 10; ++$i) {
            $tasksQueue->send(Spider::ZMQ_COMMAND_WORKER_QUIT);
        }
    }
}