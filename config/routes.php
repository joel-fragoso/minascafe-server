<?php

declare(strict_types=1);

use Minascafe\Category\Infrastructure\Http\Controller\CategoryController;
use Minascafe\Product\Infrastructure\Http\Controller\ProductController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->options('/{routes:.+}', function (Request $request, Response $response): Response {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->add(function (Request $request, $handler) {
        $response = $handler->handle($request);

        return $response
            ->withHeader('Access-Control-Allow-Origin', $_ENV['APP_WEB_URL'])
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });

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

    /*
    * Catch-all route to serve a 404 Not Found page if none of the routes match
    * NOTE: make sure this route is defined last
    */
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) {
        throw new HttpNotFoundException($request);
    });
};
