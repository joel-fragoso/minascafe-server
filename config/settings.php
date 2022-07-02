<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'settings' => [
            'slim' => [
                'display_error_details' => true,
                'log_error' => false,
                'log_error_details' => false,
                'logger' => [
                    'name' => 'minascafe-server',
                    'path' => __DIR__.'/../var/logs/app.log',
                    'level' => Logger::DEBUG,
                ],
            ],
            'doctrine' => [
                'dev_mode' => true,
                'cache_dir' => __DIR__.'/../var/cache/doctrine',
                'metadata_dirs' => [
                    __DIR__.'/../src/Category/Infrastructure/Persistence/Doctrine/Entity',
                    __DIR__.'/../src/Product/Infrastructure/Persistence/Doctrine/Entity',
                    __DIR__.'/../src/User/Infrastructure/Persistence/Doctrine/Entity',
                ],
                'connection' => [
                    'driver' => $_ENV['DOCTRINE_DRIVER'],
                    'host' => $_ENV['DOCTRINE_HOST'],
                    'port' => $_ENV['DOCTRINE_PORT'],
                    'dbname' => $_ENV['DOCTRINE_DBNAME'],
                    'user' => $_ENV['DOCTRINE_USER'],
                    'password' => $_ENV['DOCTRINE_PASSWORD'],
                    'charset' => $_ENV['DOCTRINE_CHARSET'],
                ],
            ],
        ],
    ]);
};
