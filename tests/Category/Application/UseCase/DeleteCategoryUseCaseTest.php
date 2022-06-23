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
use PHPUnit\Framework\TestCase;

final class DeleteCategoryUseCaseTest extends TestCase
{
    private CategoryRepositoryInterface $inMemoryCategoryRepository;

    private CreateCategoryUseCase $createCategoryUseCase;

    private DeleteCategoryUseCase $deleteCategoryUseCase;

    protected function setUp(): void
    {
        $this->inMemoryCategoryRepository = new CategoryRepository();
        $this->createCategoryUseCase = new CreateCategoryUseCase($this->inMemoryCategoryRepository);
        $this->deleteCategoryUseCase = new DeleteCategoryUseCase($this->inMemoryCategoryRepository);
    }

    public function testDeveSerCapazDeRemoverUmaCategoria(): void
    {
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon, true);

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

        $categoryId = '00000000-0000-0000-0000-000000000000';

        $deleteCategoryUseCaseRequest = new DeleteCategoryUseCaseRequest($categoryId);

        $this->deleteCategoryUseCase->execute($deleteCategoryUseCaseRequest);
    }
}
