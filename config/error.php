<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    return function (
        Request $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ) use ($app) {
        $response = $app->getResponseFactory()->createResponse();

        $payload = ['message' => $exception->getMessage()];

        $response->getBody()->write(json_encode($payload, \JSON_PRETTY_PRINT));

        return $response->withHeader('Content-type', 'application/json')->withStatus($exception->getCode());
    };
};
