<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Http\Middleware;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Minascafe\Shared\Domain\Exception\InvalidTokenException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Psr7Response;

final class AuthorizationMiddleware implements Middleware
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $authorization = $request->getHeader('Authorization');

        try {
            if (empty($authorization)) {
                throw new InvalidTokenException('Token não informado');
            }

            $token = str_replace('Bearer ', '', $authorization[0]);

            JWT::decode($token, new Key($_ENV['APP_SECRET_KEY'], 'HS256'));

            return $handler->handle($request);
        } catch (Exception $exception) {
            $errorMessage = 'O token é inválido';
            $errorCode = 401;

            if ($exception instanceof InvalidTokenException) {
                $errorMessage = $exception->getMessage();
                $errorCode = $exception->getCode();
            }

            $response = new Psr7Response();
            $response->getBody()->write(json_encode(['error' => $errorMessage]));

            return $response->withHeader('Content-type', 'application/json')
                        ->withStatus($errorCode);
        }
    }
}
