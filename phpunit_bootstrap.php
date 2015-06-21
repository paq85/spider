<?php

require_once __DIR__ . '/vendor/autoload.php';

\Symfony\Component\Debug\Debug::enable(E_ALL, false);


defined('FIXTURES_DIR') ? : define('FIXTURES_DIR', realpath(__DIR__ . '/src/Spider/Test/fixtures'));
if (false === FIXTURES_DIR) {
    throw new \RuntimeException('FIXTURES_DIR not found');
}

defined('BIN_DIR') ? : define('BIN_DIR', realpath(__DIR__ . '/bin'));
if (false === BIN_DIR) {
    throw new \RuntimeException('BIN_DIR not found');
}

defined('TEMP_DIR') ? : define('TEMP_DIR', realpath(__DIR__ . '/src/Spider/Test/temp'));
if (false === TEMP_DIR) {
    throw new \RuntimeException('TEMP_DIR not found');
}