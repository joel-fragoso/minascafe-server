<?php

declare(strict_types=1);

use function DI\autowire;
use DI\ContainerBuilder;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Infrastructure\Persistence\Doctrine\Repository\CategoryRepository;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        CategoryRepositoryInterface::class => autowire(CategoryRepository::class),
    ]);
};
