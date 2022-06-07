<?php

declare(strict_types=1);

use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$containerBuilder = new ContainerBuilder();

$settings = require 'config/settings.php';
$settings($containerBuilder);

$dependencies = require 'config/dependencies.php';
$dependencies($containerBuilder);

$repositories = require 'config/repositories.php';
$repositories($containerBuilder);

$container = $containerBuilder->build();

// AppFactory::setContainer($container);
$app = Bridge::create($container);
// $callableResolver = $app->getCallableResolver();

$routes = require 'config/routes.php';
$routes($app);

$settings = $container->get('settings')['slim'];

$displayErrorDetails = $settings['display_error_details'];
$logError = $settings['log_error'];
$logErrorDetails = $settings['log_error_details'];

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// $responseFactory = $app->getResponseFactory();
// $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

// $shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
// register_shutdown_function($shutdownHandler);

$app->addRoutingMiddleware();

$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

return $app;
