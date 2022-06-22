<?php

declare(strict_types=1);

namespace Minascafe\Tests\Product\Application\UseCase;

use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Infrastructure\Persistence\InMemory\Repository\CategoryRepository;
use Minascafe\Product\Application\UseCase\CreateProductUseCase;
use Minascafe\Product\Application\UseCase\CreateProductUseCaseRequest;
use Minascafe\Product\Application\UseCase\ShowAllProductsUseCase;
use Minascafe\Product\Infrastructure\Persistence\InMemory\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;

final class ShowAllProductsUseCaseTest extends TestCase
{
    private CreateCategoryUseCase $createCategoryUseCase;

    private CreateProductUseCase $createProductUseCase;

    private ShowAllProductsUseCase $showAllProductsUseCase;

    protected function setUp(): void
    {
        $inMemoryCategoryRepository = new CategoryRepository();
        $inMemoryProductRepository = new ProductRepository();
        $this->createCategoryUseCase = new CreateCategoryUseCase($inMemoryCategoryRepository);
        $this->createProductUseCase = new CreateProductUseCase($inMemoryCategoryRepository, $inMemoryProductRepository);
        $this->showAllProductsUseCase = new ShowAllProductsUseCase($inMemoryProductRepository);
    }

    public function testDeveSerCapazDeListarTodosOsProdutos(): void
    {
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon);

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId1 = $createCategoryUseCaseResponse->categoryId();
        $productName1 = 'Produto 1';
        $productPrice1 = 1.00;

        $createProductUseCaseRequest = new CreateProductUseCaseRequest($categoryId1, $productName1, $productPrice1);

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $categoryId2 = $createCategoryUseCaseResponse->categoryId();
        $productName2 = 'Produto 2';
        $productPrice2 = 1.00;

        $createProductUseCaseRequest = new CreateProductUseCaseRequest($categoryId2, $productName2, $productPrice2);

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $showAllProductsUseCaseResponse = $this->showAllProductsUseCase->execute();

        self::assertIsArray($showAllProductsUseCaseResponse->products());
        self::assertCount(2, $showAllProductsUseCaseResponse->products());
    }

    public function testDeveSerCapazDeRetornarUmJsonSerializado(): void
    {
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon);

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId1 = $createCategoryUseCaseResponse->categoryId();
        $productName1 = 'Produto 1';
        $productPrice1 = 1.00;

        $createProductUseCaseRequest = new CreateProductUseCaseRequest($categoryId1, $productName1, $productPrice1);

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $categoryId2 = $createCategoryUseCaseResponse->categoryId();
        $productName2 = 'Produto 2';
        $productPrice2 = 1.00;

        $createProductUseCaseRequest = new CreateProductUseCaseRequest($categoryId2, $productName2, $productPrice2);

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $showAllProductsUseCaseResponse = $this->showAllProductsUseCase->execute();

        $products = [];

        foreach ($showAllProductsUseCaseResponse->products() as $product) {
            $products[] = $product->toArray();
        }

        $expectedJsonSerialize = json_encode($products);

        self::assertEquals($expectedJsonSerialize, json_encode($showAllProductsUseCaseResponse));
    }
}
