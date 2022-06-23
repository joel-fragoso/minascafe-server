<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;

final class ShowAllProductsUseCase
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function execute(
        ShowAllProductsUseCaseRequest $showAllProductsUseCaseRequest
    ): ShowAllProductsUseCaseResponse {
        $products = $this->productRepository->findAll(
            $showAllProductsUseCaseRequest->active(),
            $showAllProductsUseCaseRequest->order(),
            $showAllProductsUseCaseRequest->limit(),
            $showAllProductsUseCaseRequest->offset()
        );

        return new ShowAllProductsUseCaseResponse($products);
    }
}
