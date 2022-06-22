<?php

declare(strict_types=1);

namespace Minascafe\Tests\Product\Application\UseCase;

use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Infrastructure\Persistence\InMemory\Repository\CategoryRepository;
use Minascafe\Product\Application\UseCase\CreateProductUseCase;
use Minascafe\Product\Application\UseCase\CreateProductUseCaseRequest;
use Minascafe\Product\Application\UseCase\UpdateProductUseCase;
use Minascafe\Product\Application\UseCase\UpdateProductUseCaseRequest;
use Minascafe\Product\Domain\Exception\ProductNotFoundException;
use Minascafe\Product\Infrastructure\Persistence\InMemory\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;

final class UpdateProductUseCaseTest extends TestCase
{
    private CreateCategoryUseCase $createCategoryUseCase;

    private CreateProductUseCase $createProductUseCase;

    private UpdateProductUseCase $updateProductUseCase;

    protected function setUp(): void
    {
        $inMemoryCategoryRepository = new CategoryRepository();
        $inMemoryProductRepository = new ProductRepository();
        $this->createCategoryUseCase = new CreateCategoryUseCase($inMemoryCategoryRepository);
        $this->createProductUseCase = new CreateProductUseCase($inMemoryCategoryRepository, $inMemoryProductRepository);
        $this->updateProductUseCase = new UpdateProductUseCase($inMemoryCategoryRepository, $inMemoryProductRepository);
    }

    public function testDeveSerCapazDeAtualizarUmProduto(): void
    {
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon);

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId1 = $createCategoryUseCaseResponse->categoryId();
        $productName1 = 'Produto 1';
        $productPrice1 = 1.00;

        $createProductUseCaseRequest = new CreateProductUseCaseRequest($categoryId1, $productName1, $productPrice1);

        $createProductUseCaseResponse = $this->createProductUseCase->execute($createProductUseCaseRequest);

        $productId = $createProductUseCaseResponse->productId();
        $categoryId2 = $createCategoryUseCaseResponse->categoryId();
        $productName2 = 'Product 2';
        $productPrice2 = 2.00;

        $updateProductUseCaseRequest = new UpdateProductUseCaseRequest($productId, $categoryId2, $productName2, $productPrice2);

        $updateProductUseCaseResponse = $this->updateProductUseCase->execute($updateProductUseCaseRequest);

        self::assertEquals($productId, $updateProductUseCaseResponse->productId());
        self::assertEquals($categoryId2, $updateProductUseCaseResponse->category()->id()->value());
        self::assertEquals($productName2, $updateProductUseCaseResponse->name());
        self::assertEquals($productPrice2, $updateProductUseCaseResponse->price());
    }

    public function testNaoDeveSerCapazDeAtualizarUmProdutoSemCategoria(): void
    {
        self::expectException(CategoryNotFoundException::class);

        $productId = '00000000-0000-0000-0000-000000000000';
        $categoryId = '00000000-0000-0000-0000-000000000000';
        $productName = 'Product';
        $productPrice = 1.00;

        $updateProductUseCaseRequest = new UpdateProductUseCaseRequest($productId, $categoryId, $productName, $productPrice);

        $this->updateProductUseCase->execute($updateProductUseCaseRequest);
    }

    public function testNaoDeveSerCapazDeAtualizarUmProdutoQueNaoExiste(): void
    {
        self::expectException(ProductNotFoundException::class);

        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon);

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $productId = '00000000-0000-0000-0000-000000000000';
        $categoryId = $createCategoryUseCaseResponse->categoryId();
        $productName = 'Product';
        $productPrice = 1.00;

        $updateProductUseCaseRequest = new UpdateProductUseCaseRequest($productId, $categoryId, $productName, $productPrice);

        $this->updateProductUseCase->execute($updateProductUseCaseRequest);
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

        $createProductUseCaseResponse = $this->createProductUseCase->execute($createProductUseCaseRequest);

        $productId = $createProductUseCaseResponse->productId();
        $categoryId2 = $createCategoryUseCaseResponse->categoryId();
        $productName2 = 'Product 2';
        $productPrice2 = 2.00;

        $updateProductUseCaseRequest = new UpdateProductUseCaseRequest($productId, $categoryId2, $productName2, $productPrice2);

        $updateProductUseCaseResponse = $this->updateProductUseCase->execute($updateProductUseCaseRequest);

        $expectedJsonSerialize = json_encode([
            'id' => $updateProductUseCaseResponse->productId(),
            'name' => $updateProductUseCaseResponse->name(),
            'price' => $updateProductUseCaseResponse->price(),
            'category' => $updateProductUseCaseResponse->category()->toArray(),
        ]);

        self::assertEquals($expectedJsonSerialize, json_encode($updateProductUseCaseResponse));
    }
}
