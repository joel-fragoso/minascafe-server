<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Product\Domain\Entity\Product;
use Minascafe\Product\Domain\Exception\DuplicateProductException;
use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;
use Minascafe\Product\Domain\ValueObject\ProductId;
use Minascafe\Product\Domain\ValueObject\ProductName;
use Minascafe\Product\Domain\ValueObject\ProductPrice;

final class CreateProductUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private ProductRepositoryInterface $productRepository
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
        $productPrice = $createProductUseCaseRequest->price();

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
            new ProductPrice($productPrice)
        );

        $this->productRepository->create($product);

        return new CreateProductUseCaseResponse(
            $product->id()->value(),
            $product->category(),
            $product->name()->value(),
            $product->price()->value()
        );
    }
}
