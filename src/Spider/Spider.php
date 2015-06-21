<?php
/**
 * (c) Damian Sromek <damian.sromek@gmail.com>
 */

namespace Paq\Spider;

/**
 * For now just to store "global" settings
 */
final class Spider
{

    const ZMQ_TASKS_QUEUE_DSN = 'tcp://localhost:5557';
    const ZMQ_TASKS_QUEUE_BIND_DSN = 'tcp://*:5557';
    const ZMQ_RESULTS_QUEUE_DSN = 'tcp://localhost:5558';
    const ZMQ_RESULTS_QUEUE_BIND_DSN = 'tcp://*:5558';
    const ZMQ_STATUS_QUEUE_DSN = 'tcp://localhost:5559';
    const ZMQ_STATUS_QUEUE_BIND_DSN = 'tcp://*:5559';
    const ZMQ_COMMAND_BATCH_START = 'BATCH_START%';
    const ZMQ_COMMAND_BATCH_END = 'BATCH_END%';
    const ZMQ_COMMAND_WORKER_QUIT = '%QUIT%';
}