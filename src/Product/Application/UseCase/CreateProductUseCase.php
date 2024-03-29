<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

use DateTimeImmutable;
use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Product\Domain\Entity\Product;
use Minascafe\Product\Domain\Exception\DuplicateProductException;
use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;
use Minascafe\Product\Domain\ValueObject\ProductActive;
use Minascafe\Product\Domain\ValueObject\ProductCreatedAt;
use Minascafe\Product\Domain\ValueObject\ProductId;
use Minascafe\Product\Domain\ValueObject\ProductName;
use Minascafe\Product\Domain\ValueObject\ProductPrice;
use Minascafe\Product\Domain\ValueObject\ProductUpdatedAt;
use Minascafe\Shared\Application\Adapter\CacheAdapterInterface;

final class CreateProductUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private ProductRepositoryInterface $productRepository,
        private CacheAdapterInterface $cacheAdapter,
    ) {
    }

    /**
     * @throws CategoryNotFoundException
     * @throws DuplicateProductException
     */
    public function execute(CreateProductUseCaseRequest $createProductUseCaseRequest): CreateProductUseCaseResponse
    {
        $categoryId = $createProductUseCaseRequest->categoryId();
        $productName = $createProductUseCaseRequest->name();

        $findCategory = $this->categoryRepository->findById(new CategoryId($categoryId));

        if (!$findCategory) {
            throw new CategoryNotFoundException("A categoria '{$categoryId}' não foi encontrada");
        }

        $findProduct = $this->productRepository->findByName(new ProductName($productName));

        if ($findProduct) {
            throw new DuplicateProductException("O produto '{$productName}' já existe");
        }

        $product = Product::create(
            new ProductId(ProductId::generate()),
            $findCategory,
            new ProductName($productName),
            new ProductPrice($createProductUseCaseRequest->price()),
            new ProductActive($createProductUseCaseRequest->isActive() ?? true),
            new ProductCreatedAt(new DateTimeImmutable()),
            new ProductUpdatedAt(null)
        );

        $this->productRepository->create($product);

        $this->cacheAdapter->delete('show-all-products');

        return new CreateProductUseCaseResponse(
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
