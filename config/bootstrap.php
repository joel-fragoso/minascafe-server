<?php

declare(strict_types=1);

use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

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

$useCases = require 'config/use-cases.php';
$useCases($containerBuilder);

$container = $containerBuilder->build();

$app = Bridge::create($container);

$middleware = require 'config/middleware.php';
$middleware($app);

$routes = require 'config/routes.php';
$routes($app);

$settings = $container->get('settings')['slim'];

$displayErrorDetails = $settings['display_error_details'];
$logError = $settings['log_error'];
$logErrorDetails = $settings['log_error_details'];

$app->addRoutingMiddleware();

$app->addBodyParsingMiddleware();

$app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);

return $app;
