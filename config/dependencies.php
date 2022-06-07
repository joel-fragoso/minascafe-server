<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
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
    ]);
};
