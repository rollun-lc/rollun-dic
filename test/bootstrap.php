<?php
global $argv;

error_reporting(E_ALL | E_STRICT);

// Change to the project root, to simplify resolving paths
chdir(dirname(__DIR__));

$appEnv = getenv("APP_ENV");
if ($appEnv != 'dev') {
    echo "You cannot start test if environment var APP_ENV not set in dev!";
    exit(1);
}

// Setup autoloading
require 'vendor/autoload.php';
require_once 'config/env_configurator.php';

$container = require 'config/container.php';
\rollun\dic\InsideConstruct::setContainer($container);