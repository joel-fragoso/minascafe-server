<?php

declare(strict_types=1);

use Minascafe\Category\Infrastructure\Http\Controller\CategoryController;
use Minascafe\Product\Infrastructure\Http\Controller\ProductController;
use Minascafe\User\Infrastructure\Http\Controller\SessionController;
use Minascafe\User\Infrastructure\Http\Controller\UserController;
use Minascafe\User\Infrastructure\Http\Middleware\AuthorizationMiddleware;
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

    $app->group('/autenticacao', function (RouteCollectorProxy $group) {
        $group->post('', [SessionController::class, 'create']);
    });

    $app->group('/categorias', function (RouteCollectorProxy $group) {
        $group->get('', [CategoryController::class, 'index']);
        $group->post('', [CategoryController::class, 'create'])->add(AuthorizationMiddleware::class);
        $group->get('/{id}', [CategoryController::class, 'show'])->add(AuthorizationMiddleware::class);
        $group->put('/{id}', [CategoryController::class, 'update'])->add(AuthorizationMiddleware::class);
        $group->delete('/{id}', [CategoryController::class, 'destroy'])->add(AuthorizationMiddleware::class);
    });

    $app->group('/produtos', function (RouteCollectorProxy $group) {
        $group->get('', [ProductController::class, 'index']);
        $group->post('', [ProductController::class, 'create'])->add(AuthorizationMiddleware::class);
        $group->get('/{id}', [ProductController::class, 'show'])->add(AuthorizationMiddleware::class);
        $group->put('/{id}', [ProductController::class, 'update'])->add(AuthorizationMiddleware::class);
        $group->delete('/{id}', [ProductController::class, 'destroy'])->add(AuthorizationMiddleware::class);
    });

    $app->group('/usuarios', function (RouteCollectorProxy $group) {
        $group->get('', [UserController::class, 'index']);
        $group->post('', [UserController::class, 'create']);
        $group->get('/{id}', [UserController::class, 'show']);
        $group->put('/{id}', [UserController::class, 'update']);
        $group->delete('/{id}', [UserController::class, 'destroy']);
    })->add(AuthorizationMiddleware::class);

    /*
    * Catch-all route to serve a 404 Not Found page if none of the routes match
    * NOTE: make sure this route is defined last
    */
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) {
        throw new HttpNotFoundException($request);
    });
};
