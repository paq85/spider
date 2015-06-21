<?php
/**
 * (c) Damian Sromek <damian.sromek@gmail.com>
 */

namespace Paq\Spider;


use Symfony\Component\Console\Output\OutputInterface;

class TaskResultCollector
{

    private $resultsTargetPath;

    public function __construct($resultsTargetPath)
    {
        $this->resultsTargetPath = $resultsTargetPath;
    }

    public function collect(OutputInterface $output)
    {
        $context = new \ZMQContext();
        $resultsQueue = new \ZMQSocket($context, \ZMQ::SOCKET_PULL);
        $resultsQueue->bind(Spider::ZMQ_RESULTS_QUEUE_BIND_DSN);

        $statusQueue = new \ZMQSocket($context, \ZMQ::SOCKET_PUSH);
        $statusQueue->bind(Spider::ZMQ_STATUS_QUEUE_BIND_DSN);

        $tstart = microtime(true);

        $collectedResults = 0;
        $expectedResults = PHP_INT_MAX;
        $output->writeln('Collecting Task results');

        while ($collectedResults < $expectedResults) {
            $string = $resultsQueue->recv();
            if ($string === Spider::ZMQ_COMMAND_BATCH_START) {
                //  Wait for start of batch
            } elseif (stripos($string, Spider::ZMQ_COMMAND_BATCH_END) === false) {
                $output->writeln('Got task result: ' . substr($string, 0, 20) . ' ...');
                file_put_contents($this->resultsTargetPath . '/' . md5($string) . '.result', $string); // TODO: use Symfony/Filesystem
                $output->writeln('Collected results so far: ' . ++$collectedResults);
            } else {
                $expectedResults = (int) explode('%', $string)[1];
                $output->writeln('[INFO] Trying to collect ' . $expectedResults . ' as requested by Task Loader');
            }
        }

        $tend = microtime(true);
        $totalMsec = ($tend - $tstart) * 1000;
        $output->writeln('Task results collecting finished. Got ' . $collectedResults . ' results');
        $output->writeln("Total elapsed time: $totalMsec msec");
        $output->writeln('Sending Task Result Collector info');
        $statusQueue->send($collectedResults);
    }
}