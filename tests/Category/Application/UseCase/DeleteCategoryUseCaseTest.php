<?php

declare(strict_types=1);

namespace Minascafe\Tests\Category\Application\UseCase;

use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Application\UseCase\DeleteCategoryUseCase;
use Minascafe\Category\Application\UseCase\DeleteCategoryUseCaseRequest;
use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Infrastructure\Persistence\InMemory\Repository\CategoryRepository;
use Minascafe\Shared\Infrastructure\Adapter\InMemoryCacheAdapter;
use PHPUnit\Framework\TestCase;

final class DeleteCategoryUseCaseTest extends TestCase
{
    private CategoryRepositoryInterface $inMemoryCategoryRepository;

    private CreateCategoryUseCase $createCategoryUseCase;

    private DeleteCategoryUseCase $deleteCategoryUseCase;

    protected function setUp(): void
    {
        $this->inMemoryCategoryRepository = new CategoryRepository();
        $inMemoryCacheAdapter = new InMemoryCacheAdapter();
        $this->createCategoryUseCase = new CreateCategoryUseCase(
            $this->inMemoryCategoryRepository,
            $inMemoryCacheAdapter,
        );
        $this->deleteCategoryUseCase = new DeleteCategoryUseCase(
            $this->inMemoryCategoryRepository,
            $inMemoryCacheAdapter,
        );
    }

    public function testDeveSerCapazDeRemoverUmaCategoria(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        self::assertCount(1, $this->inMemoryCategoryRepository->findAll());

        $categoryId = $createCategoryUseCaseResponse->categoryId();

        $deleteCategoryUseCaseRequest = new DeleteCategoryUseCaseRequest($categoryId);

        $this->deleteCategoryUseCase->execute($deleteCategoryUseCaseRequest);

        self::assertCount(0, $this->inMemoryCategoryRepository->findAll());
    }

    public function testNaoDeveSerCapazDeRemoverUmaCategoriaQueNaoExiste(): void
    {
        self::expectException(CategoryNotFoundException::class);

        $deleteCategoryUseCaseRequest = new DeleteCategoryUseCaseRequest('00000000-0000-0000-0000-000000000000');

        $this->deleteCategoryUseCase->execute($deleteCategoryUseCaseRequest);
    }
}
