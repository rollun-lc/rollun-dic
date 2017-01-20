<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16.01.17
 * Time: 14:13
 */

namespace rollun\test\skeleton\Api;

use PHPUnit_Framework_TestCase;
use rollun\skeleton\Api\HelloAction;
use Zend\Http\Client;

class HelloActionTest extends HelloActionTestProvider
{

    /** @var  Client */
    protected $client;

    public function setUp()
    {
        $this->client = new Client();
    }

    /**
     * @param $param
     * @param $env
     * @param $response
     * @param $accept
     * @dataProvider providerDevQuery()
     */
    public function testDevQuery($param, $env, $response, $accept)
    {
        $uri = "http://" . constant("HOST") . "/" . $param;
        $this->client->setUri($uri);
        $this->client->setHeaders([
            'Accept' => $accept,
            'APP_ENV' => 'dev'
        ]);
        $resp = $this->client->send();
        $body = $resp->getBody();
        $this->assertTrue(preg_match('/' . quotemeta($response) . '/', $body) == 1);
    }

    /**
     * @param $param
     * @param $env
     * @param $response
     * @param $accept
     * @dataProvider providerProdQuery()
     */
    public function testProdQuery($param, $env, $response, $accept)
    {
        $uri = "http://" . constant("HOST") . "/" . $param;
        $this->client->setUri($uri);
        $this->client->setHeaders([
            'Accept' => $accept,
            'APP_ENV' => "prod"
        ]);
        $resp = $this->client->send();
        $body = $resp->getBody();
        $this->assertTrue(preg_match('/' . quotemeta($response) . '/', $body) == 1);
    }
}

