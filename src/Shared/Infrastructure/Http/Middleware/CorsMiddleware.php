<?php

declare(strict_types=1);

namespace Minascafe\Shared\Infrastructure\Http\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CorsMiddleware implements Middleware
{
    /**
     * @var array<string, array<int, string>>
     */
    protected array $cors = [
        'http://localhost:3000' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'],
        'http://localhost:3333' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'],
    ];

    public function process(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'none';

        return $this->getResponse($response, $origin, $this->cors);
    }

    /**
     * @param array<string, array<int, string>> $cors
     */
    private function getAllowedMethodsString(array $cors, string $origin): string
    {
        $methods = $cors[$origin];
        if (\is_array($methods)) {
            $methods = implode(', ', $methods);
        }

        return $methods;
    }

    /**
     * @param array<string, array<int, string>> $cors
     */
    private function getOriginHeader(array $cors, string $origin): string
    {
        if (isset($cors['*'])) {
            return '*';
        }

        return $origin;
    }

    /**
     * @param array<string, array<int, string>> $cors
     */
    private function getResponse(Response $response, string $origin, array $cors): Response
    {
        if (isset($cors['*'])) {
            $origin = '*';
        }

        if (!isset($cors[$origin])) {
            return $response;
        }

        return $response
            ->withHeader('Access-Control-Allow-Origin', $this->getOriginHeader($cors, $origin))
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', $this->getAllowedMethodsString($cors, $origin));
    }
}
