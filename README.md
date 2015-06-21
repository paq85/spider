# Scalable Web Spider (Crawler) in PHP [![Build Status](https://img.shields.io/travis/paq85/spider.svg)](https://travis-ci.org/paq85/spider)

This application lets you scan the Web using many workers connected by [ZeroMQ](http://zguide.zeromq.org/).

You can specify for example 10 000 URIs to be fetched and stored in a folder using 20 workers (concurrent downloads).

    ./run_test.sh 
    Giving workers some time to connect
    Total count of Tasks put in the Queue: 173
    Waiting for acknowledgement from Task Result Collector
    Info from Task Result Collector: 173
    Informing all workers to stop

## Setup for Ubuntu 14.04
- If you don't have [ZeroMQ](http://zguide.zeromq.org/) with [PHP ZMQ](https://pecl.php.net/package/zmq) module you can install it by running `setup_zeromq.sh`
- Run `setup.sh`

## Usage
To see how it works you can look at and try `./sandbox/run_test.sh`

## Architecture
It's using ZeroMQ's [Parallel Pipeline](http://zguide.zeromq.org/php:all#Divide-and-Conquer) 

## Plans
See [TODO](TODO.md)

## Authors
[Damian Sromek](damian.sromek@gmail.com)

## License
Spider is licensed under the MIT License - see the LICENSE file for details