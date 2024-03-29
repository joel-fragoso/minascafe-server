<?php

declare(strict_types=1);

namespace Minascafe\Tests\Product\Application\UseCase;

use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Infrastructure\Persistence\InMemory\Repository\CategoryRepository;
use Minascafe\Product\Application\UseCase\CreateProductUseCase;
use Minascafe\Product\Application\UseCase\CreateProductUseCaseRequest;
use Minascafe\Product\Application\UseCase\ShowOneProductUseCase;
use Minascafe\Product\Application\UseCase\ShowOneProductUseCaseRequest;
use Minascafe\Product\Domain\Exception\ProductNotFoundException;
use Minascafe\Product\Infrastructure\Persistence\InMemory\Repository\ProductRepository;
use Minascafe\Shared\Infrastructure\Adapter\InMemoryCacheAdapter;
use PHPUnit\Framework\TestCase;

final class ShowOneProductUseCaseTest extends TestCase
{
    private CreateCategoryUseCase $createCategoryUseCase;

    private CreateProductUseCase $createProductUseCase;

    private ShowOneProductUseCase $showOneProductUseCase;

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
        $this->showOneProductUseCase = new ShowOneProductUseCase($inMemoryProductRepository);
    }

    public function testDeveSerCapazDeEncontrarUmProduto(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId = $createCategoryUseCaseResponse->categoryId();
        $productName = 'Produto';
        $productPrice = 1.00;

        $createProductUseCaseRequest = new CreateProductUseCaseRequest($categoryId, $productName, $productPrice, true);

        $createProductUseCaseResponse = $this->createProductUseCase->execute($createProductUseCaseRequest);

        $productId = $createProductUseCaseResponse->productId();

        $showOneProductUseCaseRequest = new ShowOneProductUseCaseRequest($productId);

        $showOneProductUseCaseResponse = $this->showOneProductUseCase->execute($showOneProductUseCaseRequest);

        self::assertEquals($productId, $showOneProductUseCaseResponse->productId());
        self::assertEquals($productName, $showOneProductUseCaseResponse->name());
        self::assertEquals($productPrice, $showOneProductUseCaseResponse->price());
        self::assertInstanceOf(Category::class, $showOneProductUseCaseResponse->category());
    }

    public function testNaoDeveSerCapazDeEncontrarUmProdutoQueNaoExiste(): void
    {
        self::expectException(ProductNotFoundException::class);

        $showOneProductUseCaseRequest = new ShowOneProductUseCaseRequest('00000000-0000-0000-0000-000000000000');

        $this->showOneProductUseCase->execute($showOneProductUseCaseRequest);
    }

    public function testDeveSerCapazDeRetornarUmJsonSerializado(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest(
            $createCategoryUseCaseResponse->categoryId(),
            'Produto',
            1.00,
            true
        );

        $createProductUseCaseResponse = $this->createProductUseCase->execute($createProductUseCaseRequest);

        $productId = $createProductUseCaseResponse->productId();

        $showOneProductUseCaseRequest = new ShowOneProductUseCaseRequest($productId);

        $showOneProductUseCaseResponse = $this->showOneProductUseCase->execute($showOneProductUseCaseRequest);

        $expectedJsonSerialize = json_encode([
            'id' => $showOneProductUseCaseResponse->productId(),
            'name' => $showOneProductUseCaseResponse->name(),
            'price' => $showOneProductUseCaseResponse->price(),
            'active' => $showOneProductUseCaseResponse->isActive(),
            'createdAt' => $showOneProductUseCaseResponse->createdAt(),
            'updatedAt' => $showOneProductUseCaseResponse->updatedAt(),
            'category' => $showOneProductUseCaseResponse->category()->toArray(),
        ]);

        self::assertEquals($expectedJsonSerialize, json_encode($showOneProductUseCaseResponse));
    }
}
