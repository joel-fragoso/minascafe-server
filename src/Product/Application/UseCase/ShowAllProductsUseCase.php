<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;

final class ShowAllProductsUseCase
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function execute(): ShowAllProductsUseCaseResponse
    {
        $products = $this->productRepository->findAllProducts();

        return new ShowAllProductsUseCaseResponse($products);
    }
}
