<?php

declare(strict_types=1);

use Minascafe\Category\Infrastructure\Http\Controller\CategoryController;
use Minascafe\Product\Infrastructure\Http\Controller\ProductController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/categorias', function (RouteCollectorProxy $group) {
        $group->get('', [CategoryController::class, 'index']);
        $group->post('', [CategoryController::class, 'create']);
        $group->get('/{id}', [CategoryController::class, 'show']);
        $group->put('/{id}', [CategoryController::class, 'update']);
        $group->delete('/{id}', [CategoryController::class, 'destroy']);
    });

    $app->group('/produtos', function (RouteCollectorProxy $group) {
        $group->get('', [ProductController::class, 'index']);
        $group->post('', [ProductController::class, 'create']);
        $group->get('/{id}', [ProductController::class, 'show']);
        $group->put('/{id}', [ProductController::class, 'update']);
        $group->delete('/{id}', [ProductController::class, 'destroy']);
    });
};
