<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

use DateTimeImmutable;
use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Product\Domain\Entity\Product;
use Minascafe\Product\Domain\Exception\ProductNotFoundException;
use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;
use Minascafe\Product\Domain\ValueObject\ProductActive;
use Minascafe\Product\Domain\ValueObject\ProductCreatedAt;
use Minascafe\Product\Domain\ValueObject\ProductId;
use Minascafe\Product\Domain\ValueObject\ProductName;
use Minascafe\Product\Domain\ValueObject\ProductPrice;
use Minascafe\Product\Domain\ValueObject\ProductUpdatedAt;
use Minascafe\Shared\Application\Adapter\CacheAdapterInterface;

final class UpdateProductUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private ProductRepositoryInterface $productRepository,
        private CacheAdapterInterface $cacheAdapter,
    ) {
    }

    public function execute(UpdateProductUseCaseRequest $updateProductUseCaseRequest): UpdateProductUseCaseResponse
    {
        $productId = $updateProductUseCaseRequest->productId();
        $categoryId = $updateProductUseCaseRequest->categoryId();

        $findProduct = $this->productRepository->findById(new ProductId($productId));

        if (!$findProduct) {
            throw new ProductNotFoundException("O produto '{$productId}' não foi encontrado");
        }

        $findCategory = $categoryId
            ? $this->categoryRepository->findById(new CategoryId($categoryId))
            : $findProduct->category();

        if (!$findCategory) {
            throw new CategoryNotFoundException("A categoria '{$categoryId}' não foi encontrada");
        }

        $product = Product::create(
            new ProductId($findProduct->id()->value()),
            $findCategory,
            new ProductName($updateProductUseCaseRequest->name() ?? $findProduct->name()->value()),
            new ProductPrice($updateProductUseCaseRequest->price() ?? $findProduct->price()->value()),
            new ProductActive($updateProductUseCaseRequest->isActive() ?? $findProduct->isActive()->value()),
            new ProductCreatedAt($findProduct->createdAt()->value()),
            new ProductUpdatedAt(new DateTimeImmutable())
        );

        $this->productRepository->update($product);

        $this->cacheAdapter->delete('show-all-products');

        return new UpdateProductUseCaseResponse(
            $product->id()->value(),
            $product->category(),
            $product->name()->value(),
            $product->price()->value(),
            $product->isActive()->value(),
            $product->createdAt()->value(),
            $product->updatedAt()->value()
        );
    }
}
