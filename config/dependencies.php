<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use GuzzleHttp\Client;
use Minascafe\Shared\Infrastructure\Logger\Monolog\Handler\DiscordHandler as HandlerDiscordHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
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
        LoggerInterface::class => function (ContainerInterface $c) {
            $loggerSettings = $c->get('settings')['slim']['logger'];
            $discordSettings = $c->get('settings')['slim']['discord'];

            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $client = new Client();
            $discordHandler = new HandlerDiscordHandler($client, $discordSettings['url'], $discordSettings['name']);
            $logger->pushHandler($discordHandler);

            $streamHandler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($streamHandler);

            return $logger;
        },
    ]);
};
