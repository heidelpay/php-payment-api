<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Fix broken OpenSSL lib on Travis CI
if (getenv('TRAVIS')) {
    if (!defined('CURL_SSLVERSION_TLSv1_2')) {
        define('CURL_SSLVERSION_TLSv1_2', 6);
    }
}

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'debug' => false,
    'cacheDir' => __DIR__.'/_data',
    'includePaths' => [__DIR__.'/../lib'],
    'excludePaths' => [
        __DIR__.'/../vendor'
    ]
]);
