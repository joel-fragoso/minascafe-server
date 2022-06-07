<?php

declare(strict_types=1);

use Slim\ResponseEmitter;

chdir(dirname(__DIR__));
// (require 'config/bootstrap.php')->run();
require 'config/bootstrap.php';

$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
