<?php

declare(strict_types=1);

namespace Minascafe\Tests\Product\Application\UseCase;

use DateTimeImmutable;
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
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $createProductUseCaseRequest = new CreateProductUseCaseRequest(
            $createCategoryUseCaseResponse->categoryId(),
            'Produto 1',
            1.00,
            false
        );

        $createProductUseCaseResponse = $this->createProductUseCase->execute($createProductUseCaseRequest);

        $productId = $createProductUseCaseResponse->productId();
        $categoryId = $createCategoryUseCaseResponse->categoryId();
        $productName = 'Product 2';
        $productPrice = 2.00;

        $updateProductUseCaseRequest = new UpdateProductUseCaseRequest(
            $productId,
            $categoryId,
            $productName,
            $productPrice,
            true
        );

        $updateProductUseCaseResponse = $this->updateProductUseCase->execute($updateProductUseCaseRequest);

        self::assertEquals($productId, $updateProductUseCaseResponse->productId());
        self::assertEquals($categoryId, $updateProductUseCaseResponse->category()->id()->value());
        self::assertEquals($productName, $updateProductUseCaseResponse->name());
        self::assertEquals($productPrice, $updateProductUseCaseResponse->price());
        self::assertTrue($updateProductUseCaseResponse->isActive());
    }

    public function testNaoDeveSerCapazDeAtualizarUmProdutoSemCategoria(): void
    {
        self::expectException(CategoryNotFoundException::class);

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId = $createCategoryUseCaseResponse->categoryId();
        $productName = 'Produto';
        $productPrice = 1.00;

        $createProductUseCaseRequest = new CreateProductUseCaseRequest($categoryId, $productName, $productPrice, true);

        $createProductUseCaseResponse = $this->createProductUseCase->execute($createProductUseCaseRequest);

        $updateProductUseCaseRequest = new UpdateProductUseCaseRequest(
            $createProductUseCaseResponse->productId(),
            '00000000-0000-0000-0000-000000000000',
            'Product',
            1.00,
            true
        );

        $this->updateProductUseCase->execute($updateProductUseCaseRequest);
    }

    public function testNaoDeveSerCapazDeAtualizarUmProdutoQueNaoExiste(): void
    {
        self::expectException(ProductNotFoundException::class);

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $updateProductUseCaseRequest = new UpdateProductUseCaseRequest(
            '00000000-0000-0000-0000-000000000000',
            $createCategoryUseCaseResponse->categoryId(),
            'Product',
            1.00,
            true
        );

        $this->updateProductUseCase->execute($updateProductUseCaseRequest);
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

        $createProductUseCaseResponse = $this->createProductUseCase->execute($createProductUseCaseRequest);

        $updateProductUseCaseRequest = new UpdateProductUseCaseRequest(
            $createProductUseCaseResponse->productId(),
            $createCategoryUseCaseResponse->categoryId(),
            'Product 2',
            2.00,
            true
        );

        $updateProductUseCaseResponse = $this->updateProductUseCase->execute($updateProductUseCaseRequest);

        $expectedJsonSerialize = json_encode([
            'id' => $updateProductUseCaseResponse->productId(),
            'name' => $updateProductUseCaseResponse->name(),
            'price' => $updateProductUseCaseResponse->price(),
            'active' => $updateProductUseCaseResponse->isActive(),
            'createdAt' => $updateProductUseCaseResponse->createdAt()->format(DateTimeImmutable::ATOM),
            'category' => $updateProductUseCaseResponse->category()->toArray(),
        ]);

        self::assertEquals($expectedJsonSerialize, json_encode($updateProductUseCaseResponse));
    }
}
