<?php

declare(strict_types=1);

namespace Minascafe\Tests\Category\Application\UseCase;

use DateTimeInterface;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Application\UseCase\ShowOneCategoryUseCase;
use Minascafe\Category\Application\UseCase\ShowOneCategoryUseCaseRequest;
use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Infrastructure\Persistence\InMemory\Repository\CategoryRepository;
use Minascafe\Shared\Infrastructure\Adapter\InMemoryCacheAdapter;
use PHPUnit\Framework\TestCase;

final class ShowOneCategoryUseCaseTest extends TestCase
{
    private CreateCategoryUseCase $createCategoryUseCase;

    private ShowOneCategoryUseCase $showOneCategoryUseCase;

    protected function setUp(): void
    {
        $inMemoryCategoryRepository = new CategoryRepository();
        $inMemoryCacheAdapter = new InMemoryCacheAdapter();
        $this->createCategoryUseCase = new CreateCategoryUseCase($inMemoryCategoryRepository, $inMemoryCacheAdapter);
        $this->showOneCategoryUseCase = new ShowOneCategoryUseCase($inMemoryCategoryRepository);
    }

    public function testDeveSerCapazDeEncontrarUmaCategoria(): void
    {
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon);

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId = $createCategoryUseCaseResponse->categoryId();

        $showOneCategoryUseCaseRequest = new ShowOneCategoryUseCaseRequest($categoryId);

        $showOneCategoryUseCaseResponse = $this->showOneCategoryUseCase->execute($showOneCategoryUseCaseRequest);

        self::assertEquals($categoryId, $showOneCategoryUseCaseResponse->categoryId());
        self::assertEquals($categoryName, $showOneCategoryUseCaseResponse->name());
        self::assertEquals($categoryIcon, $showOneCategoryUseCaseResponse->icon());
        self::assertInstanceOf(DateTimeInterface::class, $createCategoryUseCaseResponse->createdAt());
    }

    public function testNaoDeveSerCapazDeEncontrarUmaCategoriaQueNaoExiste(): void
    {
        self::expectException(CategoryNotFoundException::class);

        $showOneCategoryUseCaseRequest = new ShowOneCategoryUseCaseRequest('00000000-0000-0000-0000-000000000000');

        $this->showOneCategoryUseCase->execute($showOneCategoryUseCaseRequest);
    }

    public function testDeveSerCapazDeRetornarUmJsonSerializado(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId = $createCategoryUseCaseResponse->categoryId();

        $showOneCategoryUseCaseRequest = new ShowOneCategoryUseCaseRequest($categoryId);

        $showOneCategoryUseCaseResponse = $this->showOneCategoryUseCase->execute($showOneCategoryUseCaseRequest);

        $expectedJsonSerialize = json_encode([
            'id' => $showOneCategoryUseCaseResponse->categoryId(),
            'name' => $showOneCategoryUseCaseResponse->name(),
            'icon' => $showOneCategoryUseCaseResponse->icon(),
            'active' => $showOneCategoryUseCaseResponse->isActive(),
            'createdAt' => $showOneCategoryUseCaseResponse->createdAt(),
            'updatedAt' => $showOneCategoryUseCaseResponse->updatedAt(),
        ]);

        self::assertEquals($expectedJsonSerialize, json_encode($showOneCategoryUseCaseResponse));
    }
}
