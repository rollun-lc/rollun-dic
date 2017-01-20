<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16.01.17
 * Time: 14:47
 */

namespace rollun\test\skeleton\Api;

use PHPUnit_Framework_TestCase;

class HelloActionTestProvider extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function providerDevQuery()
    {
        return [
            [
                "world", "dev", "[dev] Hello world!", 'text/html'
            ],
            [
                "", "dev", "[dev] Hello !", 'text/html'
            ],
            [
                "world", "dev", "[dev] Hello world!", 'application/json'
            ],
            [
                "", "dev", "[dev] Hello !", 'application/json'
            ],
            [
                "error", "dev", 'Exception: Exception by string: [dev] Hello error! in file', 'text/html'
            ],
            [
                "error", "dev", 'Exception: Exception by string: [dev] Hello error! in file', 'application/json'
            ]
        ];
    }

    public function providerProdQuery()
    {
        return [
            [
                "world", "prod", "[prod] Hello world!", 'text/html'
            ],
            [
                "", "prod", "[prod] Hello !", 'text/html'
            ],
            [
                "world", "prod", "[prod] Hello world!", 'application/json'
            ],
            [
                "", "prod", "[prod] Hello !", 'application/json'
            ],
            [
                "error", "prod", 'We encountered a 500 Internal Server Error error.', 'text/html'
            ],
        ];
    }
}
