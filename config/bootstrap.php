<?php

declare(strict_types=1);

use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Minascafe\Shared\Application\Handler\HttpErrorHandler;
use Minascafe\Shared\Application\Handler\ShutdownHandler;
use Slim\Factory\ServerRequestCreatorFactory;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$containerBuilder = new ContainerBuilder();

if (false) { // Should be set to true in production
	$containerBuilder->enableCompilation('var/cache');
}

$settings = require 'config/settings.php';
$settings($containerBuilder);

$dependencies = require 'config/dependencies.php';
$dependencies($containerBuilder);

$repositories = require 'config/repositories.php';
$repositories($containerBuilder);

$useCases = require 'config/use-cases.php';
$useCases($containerBuilder);

$container = $containerBuilder->build();

$app = Bridge::create($container);
$callableResolver = $app->getCallableResolver();

$middleware = require 'config/middleware.php';
$middleware($app);

$routes = require 'config/routes.php';
$routes($app);

$settings = $container->get('settings')['slim'];

$displayErrorDetails = $settings['display_error_details'];
$logError = $settings['log_error'];
$logErrorDetails = $settings['log_error_details'];

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

$app->addRoutingMiddleware();

$app->addBodyParsingMiddleware();

// $error = require 'config/error.php';
// $errorHandler = $error($app);

$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

return $app->handle($request);
