<?php

declare(strict_types=1);

use function DI\autowire;
use DI\ContainerBuilder;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\DeleteCategoryUseCase;
use Minascafe\Category\Application\UseCase\ShowAllCategoriesUseCase;
use Minascafe\Category\Application\UseCase\ShowOneCategoryUseCase;
use Minascafe\Category\Application\UseCase\UpdateCategoryUseCase;
use Minascafe\Product\Application\UseCase\CreateProductUseCase;
use Minascafe\Product\Application\UseCase\DeleteProductUseCase;
use Minascafe\Product\Application\UseCase\ShowAllProductsUseCase;
use Minascafe\Product\Application\UseCase\ShowOneProductUseCase;
use Minascafe\Product\Application\UseCase\UpdateProductUseCase;
use Minascafe\User\Application\UseCase\CreateSessionUseCase;
use Minascafe\User\Application\UseCase\CreateUserUseCase;
use Minascafe\User\Application\UseCase\SendForgotPasswordUseCase;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        ShowAllCategoriesUseCase::class => autowire(ShowAllCategoriesUseCase::class),
        CreateCategoryUseCase::class => autowire(CreateCategoryUseCase::class),
        ShowOneCategoryUseCase::class => autowire(ShowOneCategoryUseCase::class),
        UpdateCategoryUseCase::class => autowire(UpdateCategoryUseCase::class),
        DeleteCategoryUseCase::class => autowire(DeleteCategoryUseCase::class),
        ShowAllProductsUseCase::class => autowire(ShowAllProductsUseCase::class),
        CreateProductUseCase::class => autowire(CreateProductUseCase::class),
        ShowOneProductUseCase::class => autowire(ShowOneProductUseCase::class),
        UpdateProductUseCase::class => autowire(UpdateProductUseCase::class),
        DeleteProductUseCase::class => autowire(DeleteProductUseCase::class),
        CreateUserUseCase::class => autowire(CreateUserUseCase::class),
        CreateSessionUseCase::class => autowire(CreateSessionUseCase::class),
        SendForgotPasswordUseCase::class => autowire(SendForgotPasswordUseCase::class),
    ]);
};
