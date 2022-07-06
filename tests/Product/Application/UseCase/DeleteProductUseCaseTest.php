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
use Minascafe\Shared\Infrastructure\Adapter\InMemoryCacheAdapter;
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
        $inMemoryCacheAdapter = new InMemoryCacheAdapter();
        $this->inMemoryProductRepository = new ProductRepository();
        $this->createCategoryUseCase = new CreateCategoryUseCase($inMemoryCategoryRepository, $inMemoryCacheAdapter);
        $this->createProductUseCase = new CreateProductUseCase(
            $inMemoryCategoryRepository,
            $this->inMemoryProductRepository,
            $inMemoryCacheAdapter,
        );
        $this->deleteProductUseCase = new DeleteProductUseCase(
            $this->inMemoryProductRepository,
            $inMemoryCacheAdapter,
        );
    }

    public function testDeveSerCapazDeRemoverUmProduto(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest($createCategoryUseCaseResponse->categoryId(), 'Produto', 1.00, true);

        $createProductUseCaseResponse = $this->createProductUseCase->execute($createProductUseCaseRequest);

        self::assertCount(1, $this->inMemoryProductRepository->findAll());

        $productId = $createProductUseCaseResponse->productId();

        $deleteProductUseCaseRequest = new DeleteProductUseCaseRequest($productId);

        $this->deleteProductUseCase->execute($deleteProductUseCaseRequest);

        self::assertCount(0, $this->inMemoryProductRepository->findAll());
    }

    public function testNaoDeveSerCapazDeRemoverUmProdutoQueNaoExiste(): void
    {
        self::expectException(ProductNotFoundException::class);

        $deleteProductUseCaseRequest = new DeleteProductUseCaseRequest('00000000-0000-0000-0000-000000000000');

        $this->deleteProductUseCase->execute($deleteProductUseCaseRequest);
    }
}
