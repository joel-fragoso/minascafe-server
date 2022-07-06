<?php

declare(strict_types=1);

namespace Minascafe\Tests\Product\Application\UseCase;

use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Infrastructure\Persistence\InMemory\Repository\CategoryRepository;
use Minascafe\Product\Application\UseCase\CreateProductUseCase;
use Minascafe\Product\Application\UseCase\CreateProductUseCaseRequest;
use Minascafe\Product\Application\UseCase\ShowAllProductsUseCase;
use Minascafe\Product\Application\UseCase\ShowAllProductsUseCaseRequest;
use Minascafe\Product\Infrastructure\Persistence\InMemory\Repository\ProductRepository;
use Minascafe\Shared\Infrastructure\Adapter\InMemoryCacheAdapter;
use PHPUnit\Framework\TestCase;

final class ShowAllProductsUseCaseTest extends TestCase
{
    private CreateCategoryUseCase $createCategoryUseCase;

    private CreateProductUseCase $createProductUseCase;

    private ShowAllProductsUseCase $showAllProductsUseCase;

    protected function setUp(): void
    {
        $inMemoryCategoryRepository = new CategoryRepository();
        $inMemoryCacheAdapter = new InMemoryCacheAdapter();
        $inMemoryProductRepository = new ProductRepository();
        $this->createCategoryUseCase = new CreateCategoryUseCase($inMemoryCategoryRepository, $inMemoryCacheAdapter);
        $this->createProductUseCase = new CreateProductUseCase(
            $inMemoryCategoryRepository,
            $inMemoryProductRepository,
            $inMemoryCacheAdapter,
        );
        $this->showAllProductsUseCase = new ShowAllProductsUseCase($inMemoryProductRepository, $inMemoryCacheAdapter);
    }

    public function testDeveSerCapazDeListarTodosOsProdutos(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest(
            $createCategoryUseCaseResponse->categoryId(),
            'Produto 1',
            1.00,
            true
        );

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest(
            $createCategoryUseCaseResponse->categoryId(),
            'Produto 2',
            1.00,
            true
        );

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $showAllProductsUseCaseRequest = new ShowAllProductsUseCaseRequest(order: 'name');

        $showAllProductsUseCaseResponse = $this->showAllProductsUseCase->execute($showAllProductsUseCaseRequest);

        self::assertIsArray($showAllProductsUseCaseResponse->products());
        self::assertCount(2, $showAllProductsUseCaseResponse->products());
    }

    public function testDeveSerCapazDeListarTodosOsProdutosAtivos(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest(
            $createCategoryUseCaseResponse->categoryId(),
            'Produto 1',
            1.00,
            false
        );

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest(
            $createCategoryUseCaseResponse->categoryId(),
            'Produto 2',
            1.00,
            true
        );

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $showAllProductsUseCaseRequest = new ShowAllProductsUseCaseRequest(active: 1, order: 'name');

        $showAllProductsUseCaseResponse = $this->showAllProductsUseCase->execute($showAllProductsUseCaseRequest);

        self::assertIsArray($showAllProductsUseCaseResponse->products());
        self::assertCount(1, $showAllProductsUseCaseResponse->products());
    }

    public function testDeveSerCapazDeListarTodosOsProdutosInativos(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest(
            $createCategoryUseCaseResponse->categoryId(),
            'Produto 1',
            1.00,
            false
        );

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest(
            $createCategoryUseCaseResponse->categoryId(),
            'Produto 2',
            1.00,
            true
        );

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $showAllProductsUseCaseRequest = new ShowAllProductsUseCaseRequest(active: 0, order: 'name');

        $showAllProductsUseCaseResponse = $this->showAllProductsUseCase->execute($showAllProductsUseCaseRequest);

        self::assertIsArray($showAllProductsUseCaseResponse->products());
        self::assertCount(1, $showAllProductsUseCaseResponse->products());
    }

    public function testDeveSerCapazDeRetornarUmJsonSerializado(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest(
            $createCategoryUseCaseResponse->categoryId(),
            'Produto 1',
            1.00,
            true
        );

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest(
            $createCategoryUseCaseResponse->categoryId(),
            'Produto 2',
            1.00,
            true
        );

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $showAllProductsUseCaseRequest = new ShowAllProductsUseCaseRequest(order: 'name');

        $showAllProductsUseCaseResponse = $this->showAllProductsUseCase->execute($showAllProductsUseCaseRequest);

        $products = [];

        foreach ($showAllProductsUseCaseResponse->products() as $product) {
            $products[] = $product->toArray();
        }

        $expectedJsonSerialize = json_encode($products);

        self::assertEquals($expectedJsonSerialize, json_encode($showAllProductsUseCaseResponse));
    }
}
