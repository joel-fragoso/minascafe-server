<?php

declare(strict_types=1);

use Minascafe\Shared\Infrastructure\Http\Middleware\CorsMiddleware;
use Selective\Validation\Middleware\ValidationExceptionMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(ValidationExceptionMiddleware::class);
    $app->add(CorsMiddleware::class);
};
