<?php

declare(strict_types=1);

namespace Minascafe\Tests\Product\Application\UseCase;

use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Infrastructure\Persistence\InMemory\Repository\CategoryRepository;
use Minascafe\Product\Application\UseCase\CreateProductUseCase;
use Minascafe\Product\Application\UseCase\CreateProductUseCaseRequest;
use Minascafe\Product\Application\UseCase\DeleteProductUseCase;
use Minascafe\Product\Application\UseCase\DeleteProductUseCaseRequest;
use Minascafe\Product\Domain\Exception\ProductNotFoundException;
use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;
use Minascafe\Product\Infrastructure\Persistence\InMemory\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;

final class DeleteProductUseCaseTest extends TestCase
{
    private ProductRepositoryInterface $inMemoryProductRepository;

    private CreateCategoryUseCase $createCategoryUseCase;

    private CreateProductUseCase $createProductUseCase;

    private DeleteProductUseCase $deleteProductUseCase;

    protected function setUp(): void
    {
        $inMemoryCategoryRepository = new CategoryRepository();
        $this->inMemoryProductRepository = new ProductRepository();
        $this->createCategoryUseCase = new CreateCategoryUseCase($inMemoryCategoryRepository);
        $this->createProductUseCase = new CreateProductUseCase($inMemoryCategoryRepository, $this->inMemoryProductRepository);
        $this->deleteProductUseCase = new DeleteProductUseCase($this->inMemoryProductRepository);
    }

    public function testDeveSerCapazDeRemoverUmProduto(): void
    {
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon);

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId = $createCategoryUseCaseResponse->categoryId();
        $productName = 'Produto';
        $productPrice = 1.00;

        $createProductUseCaseRequest = new CreateProductUseCaseRequest($categoryId, $productName, $productPrice);

        $createProductUseCaseResponse = $this->createProductUseCase->execute($createProductUseCaseRequest);

        self::assertCount(1, $this->inMemoryProductRepository->findAllProducts());

        $productId = $createProductUseCaseResponse->productId();

        $deleteProductUseCaseRequest = new DeleteProductUseCaseRequest($productId);

        $this->deleteProductUseCase->execute($deleteProductUseCaseRequest);

        self::assertCount(0, $this->inMemoryProductRepository->findAllProducts());
    }

    public function testNaoDeveSerCapazDeRemoverUmProdutoQueNaoExiste(): void
    {
        self::expectException(ProductNotFoundException::class);

        $productId = '00000000-0000-0000-0000-000000000000';

        $deleteProductUseCaseRequest = new DeleteProductUseCaseRequest($productId);

        $this->deleteProductUseCase->execute($deleteProductUseCaseRequest);
    }
}
