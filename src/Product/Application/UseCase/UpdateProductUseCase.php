<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Product\Domain\Entity\Product;
use Minascafe\Product\Domain\Exception\ProductNotFoundException;
use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;
use Minascafe\Product\Domain\ValueObject\ProductId;
use Minascafe\Product\Domain\ValueObject\ProductName;

final class UpdateProductUseCase
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly ProductRepositoryInterface $productRepository)
    {
    }

    public function execute(UpdateProductUseCaseRequest $updateProductUseCaseRequest): UpdateProductUseCaseResponse
    {
        $productId = $updateProductUseCaseRequest->productId();
        $categoryId = $updateProductUseCaseRequest->categoryId();
        $productName = $updateProductUseCaseRequest->name();

        $findCategory = $this->categoryRepository->findById(new CategoryId($categoryId));

        if (!$findCategory) {
            throw new CategoryNotFoundException("A categoria '{$categoryId}' não foi encontrada");
        }

        $findProduct = $this->productRepository->findById(new ProductId($productId));

        if (!$findProduct) {
            throw new ProductNotFoundException("O produto '{$productId}' não foi encontrado");
        }

        $product = Product::create(
            new ProductId($findProduct->id()->value()),
            $findCategory,
            new ProductName($productName)
        );

        $this->productRepository->update($product);

        return new UpdateProductUseCaseResponse(
            $product->id()->value(),
            $product->category(),
            $product->name()->value()
        );
    }
}
