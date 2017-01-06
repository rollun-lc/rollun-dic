<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 06.01.17
 * Time: 12:59 PM
 */


$nameEnvVars = [
    'APP_ENV',
    'MACHINE_NAME',
    'HOST',
];
$config = include __DIR__ . '/env_config.php';

$configurator = function () use ($nameEnvVars, $config){
    foreach ($nameEnvVars as $var) {
        $val = getenv($var) ? getenv($var) : isset($config[$var]) ? $config[$var] : null;
        if(isset($val)) {
            define($var, $val);
        }else {
            throw new \Exception("Env $var var not set!");
        }
    }
};

return $configurator();