<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.01.17
 * Time: 14:19
 */

namespace rollun\skeleton\Middleware;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CronExceptionMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming client or server request and return a response,
     * optionally delegating to the next middleware component to create the response.
     *
     * @param RequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     * @throws \Exception
     */
    public function process(RequestInterface $request, DelegateInterface $delegate)
    {
        throw new \Exception("If use /api/cron route, you mast usage rollun-com/rollun-callback lib with cronMiddleware!");
    }
}
