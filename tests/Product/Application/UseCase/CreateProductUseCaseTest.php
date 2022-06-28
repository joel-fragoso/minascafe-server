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
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId = $createCategoryUseCaseResponse->categoryId();
        $productName = 'Produto';
        $productPrice = 1.00;

        $createProductUseCaseRequest = new CreateProductUseCaseRequest($categoryId, $productName, $productPrice, true);

        $createProductUseCaseResponse = $this->createProductUseCase->execute($createProductUseCaseRequest);

        self::assertNotNull($createProductUseCaseResponse->productId());
        self::assertInstanceOf(Category::class, $createProductUseCaseResponse->category());
        self::assertEquals($productName, $createProductUseCaseResponse->name());
        self::assertEquals($productPrice, $createProductUseCaseResponse->price());
        self::assertTrue($createProductUseCaseResponse->isActive());
    }

    public function testNaoDeveSerCapazDeCriarUmProdutoSemCategoria(): void
    {
        self::expectException(CategoryNotFoundException::class);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest(
            '00000000-0000-0000-0000-000000000000',
            'Produto',
            1.00,
            true
        );

        $this->createProductUseCase->execute($createProductUseCaseRequest);
    }

    public function testNaoDeveSerCapazDeCriarUmProdutoComOMesmoNome(): void
    {
        self::expectException(DuplicateProductException::class);

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest(
            $createCategoryUseCaseResponse->categoryId(),
            'Produto',
            1.00,
            true
        );

        $this->createProductUseCase->execute($createProductUseCaseRequest);

        $this->createProductUseCase->execute($createProductUseCaseRequest);
    }

    public function testDeveSerCapazDeRetornarUmJsonSerializado(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest($createCategoryUseCaseResponse->categoryId(), 'Produto', 1.00, true);

        $createProductUseCaseResponse = $this->createProductUseCase->execute($createProductUseCaseRequest);

        $expectedJsonSerialize = json_encode([
            'id' => $createProductUseCaseResponse->productId(),
            'name' => $createProductUseCaseResponse->name(),
            'price' => $createProductUseCaseResponse->price(),
            'active' => $createProductUseCaseResponse->isActive(),
            'createdAt' => $createProductUseCaseResponse->createdAt(),
            'category' => $createProductUseCaseResponse->category()->toArray(),
        ]);

        self::assertEquals($expectedJsonSerialize, json_encode($createProductUseCaseResponse));
    }
}
