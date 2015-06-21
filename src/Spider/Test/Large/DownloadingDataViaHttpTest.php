<?php
/**
 * (c) Damian Sromek <damian.sromek@gmail.com>
 */

namespace Paq\Spider\Test\Large;


use Paq\Spider\Worker\HttpWorker;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class DownloadingDataViaHttpTest extends \PHPUnit_Framework_TestCase
{

    public function testHttpDataDownloadUsingManyWorkers()
    {
        $filesystem = new Filesystem();
        $targetDirPath = TEMP_DIR . '/' . uniqid('test_http_download');
        $filesystem->mkdir($targetDirPath);

        $workersManager = new Process('php ' . BIN_DIR . '/spider.php worker:start-many -c 3');
        $workersManager->start();
        $this->assertTrue($workersManager->isRunning(), 'Workers Manager should be working');

        $collector = new Process('php ' . BIN_DIR . '/spider.php collector:start --target-folder=' . $targetDirPath);
        $collector->setIdleTimeout(10); // there should be an output/result at least once every 10 seconds
        $collector->start();
        $this->assertTrue($collector->isRunning(), 'Task Results Collector should be working');

        $taskLoader = new Process('php ' . BIN_DIR . '/spider.php tasks:load < ' . FIXTURES_DIR . '/uris.txt');
        $taskLoader->setTimeout(120); // 120 seconds is enough to complete the task
        $taskLoader->start();

        $this->assertTrue($taskLoader->isRunning(), 'Task Loader should be working');

        while ($taskLoader->isRunning()) {
            sleep(1); // waiting for process to complete
        }

        $taskLoaderOutput = $taskLoader->getOutput() . $taskLoader->getErrorOutput();
        $this->assertContains('Total count of Tasks put in the Queue: 10', $taskLoaderOutput, 'Task Loader should have loaded 10 Tasks');
        $this->assertContains('Waiting for acknowledgement from Task Result Collector', $taskLoaderOutput, 'Task Loader should have been waiting for Task Result Collector acknowledgement');
        $this->assertContains('Informing all workers to stop', $taskLoaderOutput, 'Task Loader should have inform Workers to stop');

        $fi = new \FilesystemIterator($targetDirPath, \FilesystemIterator::SKIP_DOTS);
        $this->assertEquals(10, iterator_count($fi), '10 Task Result Files expected');
    }

    // TODO: URIs with 404, 500, wrong URI ...
}
