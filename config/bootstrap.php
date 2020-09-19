<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

// load all the .env files
(new Dotenv(false))->loadEnv(dirname(__DIR__) . '/.env');

$_SERVER += $_ENV;

$env = ($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null) ?: 'dev';
$_ENV['APP_ENV'] = $env;
$_SERVER['APP_ENV'] = $env;

$_SERVER['APP_DEBUG'] ??= $_ENV['APP_DEBUG'] ?? 'prod' !== $_SERVER['APP_ENV'];

$debug = (int)$_SERVER['APP_DEBUG'] || filter_var($_SERVER['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
$_ENV['APP_DEBUG'] = $debug;
$_SERVER['APP_DEBUG'] = $debug;
