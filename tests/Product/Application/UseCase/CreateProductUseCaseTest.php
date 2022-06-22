<?php

declare(strict_types=1);

namespace Minascafe\Tests\Product\Application\UseCase;

use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Infrastructure\Persistence\InMemory\Repository\CategoryRepository;
use Minascafe\Product\Application\UseCase\CreateProductUseCase;
use Minascafe\Product\Application\UseCase\CreateProductUseCaseRequest;
use Minascafe\Product\Domain\Exception\DuplicateProductException;
use Minascafe\Product\Infrastructure\Persistence\InMemory\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;

final class CreateProductUseCaseTest extends TestCase
{
    private CreateCategoryUseCase $createCategoryUseCase;

    private CreateProductUseCase $createProductUseCase;

    protected function setUp(): void
    {
        $inMemoryCategoryRepository = new CategoryRepository();
        $inMemoryProductRepository = new ProductRepository();
        $this->createCategoryUseCase = new CreateCategoryUseCase($inMemoryCategoryRepository);
        $this->createProductUseCase = new CreateProductUseCase($inMemoryCategoryRepository, $inMemoryProductRepository);
    }

    public function testDeveSerCapazDeCriarUmProduto(): void
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

        self::assertNotNull($createProductUseCaseResponse->productId());
        self::assertInstanceOf(Category::class, $createProductUseCaseResponse->category());
        self::assertEquals($productName, $createProductUseCaseResponse->name());
        self::assertEquals($productPrice, $createProductUseCaseResponse->price());
    }

    public function testNaoDeveSerCapazDeCriarUmProdutoSemCategoria(): void
    {
        self::expectException(CategoryNotFoundException::class);

        $categoryId = '00000000-0000-0000-0000-000000000000';
        $productName = 'Produto';
        $productPrice = 1.00;

        $createProductUseCaseRequest = new CreateProductUseCaseRequest($categoryId, $productName, $productPrice);

        $this->createProductUseCase->execute($createProductUseCaseRequest);
    }

    public function testNaoDeveSerCapazDeCriarUmProdutoComOMesmoNome(): void
    {
        self::expectException(DuplicateProductException::class);

        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon);

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId = $createCategoryUseCaseResponse->categoryId();
        $productName = 'Produto';
        $productPrice = 1.00;

        $createProductUseCaseRequest = new CreateProductUseCaseRequest($categoryId, $productName, $productPrice);

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $this->createProductUseCase->execute($createProductUseCaseRequest);
    }

    public function testDeveSerCapazDeRetornarUmJsonSerializado(): void
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

        $expectedJsonSerialize = json_encode([
            'id' => $createProductUseCaseResponse->productId(),
            'name' => $createProductUseCaseResponse->name(),
            'price' => $createProductUseCaseResponse->price(),
            'category' => $createProductUseCaseResponse->category()->toArray(),
        ]);

        self::assertEquals($expectedJsonSerialize, json_encode($createProductUseCaseResponse));
    }
}
