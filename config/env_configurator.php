<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 06.01.17
 * Time: 12:59 PM
 */
global $argv;

$config = include __DIR__ . '/env_config.php';

$nameEnvVars = [
    'APP_ENV',
    'MACHINE_NAME',
    'HOST',
];

$configurator = function () use ($nameEnvVars, $config, $argv) {

    $argvAppEnv = function () use ($argv) {
        $match = [];
        if (isset($argv)) {
            foreach ($argv as $value) {
                if (preg_match('/^APP_ENV=([\w-_]+)/', $value, $match)) {
                    return $match[1];
                }
            }
        }
        return null;
    };

    foreach ($nameEnvVars as $key) {
        $value = getenv($key) ? getenv($key) : null;

        if (!isset($value)) {
            if (isset($config[$key])) {
                $value = $config[$key];
            } else {
                throw new RuntimeException("Env var $key not set!");
            }
        }

        if ($key === 'APP_ENV' && $value === 'dev') {
            $value = isset($_SERVER['HTTP_APP_ENV']) ? $_SERVER['HTTP_APP_ENV'] : $value;
            $argvAppEnv = $argvAppEnv();
            $value = isset($argvAppEnv) ? $argvAppEnv : $value;
        }

        if (!defined($key)) {
            define($key, $value);
        }
        unset($config[$key]);
    }
    foreach ($config as $key => $value) {
        $value = getenv($key) ? getenv($key) : $value;
        if (!defined($key)) {
            define($key, $value);
        }
    }
};

return $configurator();