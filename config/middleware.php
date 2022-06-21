<?php

declare(strict_types=1);

use Selective\Validation\Middleware\ValidationExceptionMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(ValidationExceptionMiddleware::class);
};
