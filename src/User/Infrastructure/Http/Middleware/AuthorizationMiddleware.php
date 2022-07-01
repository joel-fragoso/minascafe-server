<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Http\Middleware;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

final class AuthorizationMiddleware implements Middleware
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $authorization = $request->getHeader('Authorization');

        if (empty($authorization)) {
            throw new Exception('Token não informado', 401);
        }

        try {
            $token = str_replace('Bearer ', '', $authorization[0]);

            JWT::decode($token, new Key($_ENV['APP_SECRET_KEY'], 'HS256'));

            return $handler->handle($request);
        } catch (Exception $exception) {
            throw new Exception('O token é inválido', 401);
        }
    }
}
