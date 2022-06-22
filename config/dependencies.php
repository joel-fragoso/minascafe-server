<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Selective\Validation\Encoder\JsonEncoder;
use Selective\Validation\Middleware\ValidationExceptionMiddleware;
use Selective\Validation\Transformer\ErrorDetailsResultTransformer;
use Slim\App;
use Slim\Factory\AppFactory;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        EntityManagerInterface::class => function (ContainerInterface $c): EntityManager {
            $settings = $c->get('settings')['doctrine'];

            $cache = new PhpFilesAdapter(directory: $settings['cache_dir']);

            $config = ORMSetup::createAttributeMetadataConfiguration(
                $settings['metadata_dirs'],
                $settings['dev_mode'],
                null,
                $cache
            );

            return EntityManager::create($settings['connection'], $config);
        },
        ValidationExceptionMiddleware::class => function (ContainerInterface $c) {
            $factory = $c->get(ResponseFactoryInterface::class);

            return new ValidationExceptionMiddleware($factory, new ErrorDetailsResultTransformer(), new JsonEncoder());
        },
        ResponseFactoryInterface::class => function (ContainerInterface $c) {
            $app = $c->get(App::class);

            return $app->getResponseFactory();
        },
        App::class => function (ContainerInterface $c) {
            AppFactory::setContainer($c);

            return AppFactory::create();
        },
    ]);
};
