<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 19.12.16
 * Time: 11:45 AM
 */

use rollun\logger\Logger;
use rollun\logger\LoggerFactory;
use \rollun\logger\LogWriter\FileLogWriter;
use \rollun\logger\LogWriter\FileLogWriterFactory;
use \rollun\installer\Command;
use \rollun\logger\Installer as LoggerInstaller;

return [
    'logWriter' => [
        FileLogWriter::class => [
            FileLogWriterFactory::FILE_NAME_KEY =>
                realpath(Command::getDataDir() . DIRECTORY_SEPARATOR .
                    LoggerInstaller::LOGS_DIR . DIRECTORY_SEPARATOR . LoggerInstaller::LOGS_FILE)
        ]
    ],
    'services' => [
        'factories' => [
            FileLogWriter::class => FileLogWriterFactory::class,
            Logger::class => LoggerFactory::class,
        ],
        'aliases' =>[
            'logWriter' => FileLogWriter::class,
            'logger' => Logger::class,
        ]
    ]
];
