<?php

declare(strict_types=1);

use function DI\autowire;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Minascafe\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use Minascafe\Product\Infrastructure\Persistence\Doctrine\Entity\Product;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;
use Psr\Container\ContainerInterface;
use Minascafe\Category\Infrastructure\Persistence\Doctrine\Repository\CategoryRepository;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        CategoryRepositoryInterface::class => function (ContainerInterface $c) {
			return $c->get(EntityManagerInterface::class)->getRepository(Category::class);
		},
		ProductRepositoryInterface::class => function (ContainerInterface $c) {
			return $c->get(EntityManagerInterface::class)->getRepository(Product::class);
		}
    ]);
};
