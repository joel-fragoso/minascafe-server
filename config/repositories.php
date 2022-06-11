<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;
use Minascafe\Product\Infrastructure\Persistence\Doctrine\Entity\Product;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        CategoryRepositoryInterface::class => function (ContainerInterface $c) {
            return $c->get(EntityManagerInterface::class)->getRepository(Category::class);
        },
        ProductRepositoryInterface::class => function (ContainerInterface $c) {
            return $c->get(EntityManagerInterface::class)->getRepository(Product::class);
        },
    ]);
};
