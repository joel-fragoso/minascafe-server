<?php

declare(strict_types=1);

namespace Minascafe\Tests\Category\Application\UseCase;

use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Application\UseCase\ShowOneCategoryUseCase;
use Minascafe\Category\Application\UseCase\ShowOneCategoryUseCaseRequest;
use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Infrastructure\Persistence\InMemory\Repository\CategoryRepository;
use PHPUnit\Framework\TestCase;

final class ShowOneCategoryUseCaseTest extends TestCase
{
    private CreateCategoryUseCase $createCategoryUseCase;

    private ShowOneCategoryUseCase $showOneCategoryUseCase;

    protected function setUp(): void
    {
        $inMemoryCategoryRepository = new CategoryRepository();
        $this->createCategoryUseCase = new CreateCategoryUseCase($inMemoryCategoryRepository);
        $this->showOneCategoryUseCase = new ShowOneCategoryUseCase($inMemoryCategoryRepository);
    }

    public function testDeveSerCapazDeEncontrarUmaCategoria(): void
    {
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon, true);

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId = $createCategoryUseCaseResponse->categoryId();

        $showOneCategoryUseCaseRequest = new ShowOneCategoryUseCaseRequest($categoryId);

        $showOneCategoryUseCaseResponse = $this->showOneCategoryUseCase->execute($showOneCategoryUseCaseRequest);

        self::assertEquals($categoryId, $showOneCategoryUseCaseResponse->categoryId());
        self::assertEquals($categoryName, $showOneCategoryUseCaseResponse->name());
        self::assertEquals($categoryIcon, $showOneCategoryUseCaseResponse->icon());
    }

    public function testNaoDeveSerCapazDeEncontrarUmaCategoriaQueNaoExiste(): void
    {
        self::expectException(CategoryNotFoundException::class);

        $categoryId = '00000000-0000-0000-0000-000000000000';

        $showOneCategoryUseCaseRequest = new ShowOneCategoryUseCaseRequest($categoryId);

        $this->showOneCategoryUseCase->execute($showOneCategoryUseCaseRequest);
    }

    public function testDeveSerCapazDeRetornarUmJsonSerializado(): void
    {
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon, true);

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId = $createCategoryUseCaseResponse->categoryId();

        $showOneCategoryUseCaseRequest = new ShowOneCategoryUseCaseRequest($categoryId);

        $showOneCategoryUseCaseResponse = $this->showOneCategoryUseCase->execute($showOneCategoryUseCaseRequest);

        $expectedJsonSerialize = json_encode([
            'id' => $showOneCategoryUseCaseResponse->categoryId(),
            'name' => $showOneCategoryUseCaseResponse->name(),
            'icon' => $showOneCategoryUseCaseResponse->icon(),
            'active' => $showOneCategoryUseCaseResponse->isActive(),
        ]);

        self::assertEquals($expectedJsonSerialize, json_encode($showOneCategoryUseCaseResponse));
    }
}
