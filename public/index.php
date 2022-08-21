<?php

declare(strict_types=1);

use Minascafe\Shared\Application\ResponseEmitter\ResponseEmitter;

chdir(dirname(__DIR__));
$response = require 'config/bootstrap.php';
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
