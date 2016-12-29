<?php

// try http://__zaboy-rest/api/rest/index_StoreMiddleware?fNumberOfHours=8&fWeekday=Monday
// Change to the project root, to simplify resolving paths
chdir(dirname(__DIR__));

require 'vendor/autoload.php';

use Zend\Diactoros\Server;
use zaboy\rest\Pipe\MiddlewarePipeOptions;
use zaboy\rest\Pipe\Factory\RestRqlFactory;
use Zend\Stratigility\Middleware\ErrorHandler;
use Zend\Stratigility\Middleware\NotFoundHandler;
use Zend\Stratigility\NoopFinalHandler;

// Define application environment - 'dev' or 'prop'
if (getenv('APP_ENV') === 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $env = 'develop';
}

$container = include 'config/container.php';

$server = Server::createServer(function ($req, $resp, $next) {
    return "Hello World!";
}, $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
$server->listen();


