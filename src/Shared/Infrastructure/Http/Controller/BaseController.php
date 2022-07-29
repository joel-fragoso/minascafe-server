<?php

declare(strict_types=1);

namespace Minascafe\Shared\Infrastructure\Http\Controller;

use Psr\Http\Message\ResponseInterface as Response;

abstract class BaseController
{
    /**
     * @param array<int|string, mixed> $payload
     */
    public function jsonResponse(Response $response, array $payload = [], int $code = 200): Response
    {
        $json = json_encode($payload, \JSON_PRETTY_PRINT);
        $response->getBody()->write($json);

        if (0 === $code || 1000 <= $code) {
            $code = 500;
        }

        return $response->withHeader('Content-type', 'application/json')->withStatus($code);
    }
}
