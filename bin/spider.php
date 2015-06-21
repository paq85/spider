#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

\Symfony\Component\Debug\Debug::enable(E_ALL, false);

$config = require_once((file_exists(__DIR__ . '/../config/config.php')) ? __DIR__ . '/../config/config.php' : __DIR__ . '/../config/config.dist.php');

$application = new \Paq\Spider\Application\SpiderApp($config);
$application->run();