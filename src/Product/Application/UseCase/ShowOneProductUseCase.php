<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

use Minascafe\Product\Domain\Exception\ProductNotFoundException;
use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;
use Minascafe\Product\Domain\ValueObject\ProductId;

final class ShowOneProductUseCase
{
    public function __construct(private readonly ProductRepositoryInterface $productRepository)
    {
    }

    public function execute(ShowOneProductUseCaseRequest $showOneProductUseCaseRequest): ShowOneProductUseCaseResponse
    {
        $productId = $showOneProductUseCaseRequest->productId();

        $findProduct = $this->productRepository->findById(new ProductId($productId));

        if (!$findProduct) {
            throw new ProductNotFoundException("O produto '{$productId}' não foi encontrado");
        }

        return new ShowOneProductUseCaseResponse(
            $findProduct->id()->value(),
            $findProduct->category(),
            $findProduct->name()->value(),
        );
    }
}
