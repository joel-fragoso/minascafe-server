<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

use Minascafe\Product\Domain\Exception\ProductNotFoundException;
use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;
use Minascafe\Product\Domain\ValueObject\ProductId;

final class DeleteProductUseCase
{
    public function __construct(private readonly ProductRepositoryInterface $productRepository)
    {
    }

    public function execute(DeleteProductUseCaseRequest $deleteProductUseCaseRequest): void
    {
        $productId = $deleteProductUseCaseRequest->productId();

        $findProduct = $this->productRepository->findById(new ProductId($productId));

        if (!$findProduct) {
            throw new ProductNotFoundException("O produto '{$productId}' não foi encontrado");
        }

        $this->productRepository->delete($findProduct);
    }
}
