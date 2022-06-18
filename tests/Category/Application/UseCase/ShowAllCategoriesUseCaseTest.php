<?php

declare(strict_types=1);

namespace Minascafe\Tests\Category\Application\UseCase;

use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Application\UseCase\ShowAllCategoriesUseCase;
use Minascafe\Category\Infrastructure\Persistence\InMemory\Repository\CategoryRepository;
use PHPUnit\Framework\TestCase;

final class ShowAllCategoriesUseCaseTest extends TestCase
{
    private CreateCategoryUseCase $createCategoryUseCase;

    private ShowAllCategoriesUseCase $showAllCategoriesUseCase;

    protected function setUp(): void
    {
        $inMemoryCategoryRepository = new CategoryRepository();
        $this->createCategoryUseCase = new CreateCategoryUseCase($inMemoryCategoryRepository);
        $this->showAllCategoriesUseCase = new ShowAllCategoriesUseCase($inMemoryCategoryRepository);
    }

    public function testDeveSerCapazDeListarTodasAsCategorias(): void
    {
        $categoryName1 = 'Categoria 1';
        $categoryIcon1 = 'NomeDoIcone1';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName1, $categoryIcon1);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryName2 = 'Categoria 2';
        $categoryIcon2 = 'NomeDoIcone2';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName2, $categoryIcon2);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $showAllCategoriesUseCaseResponse = $this->showAllCategoriesUseCase->execute();

        self::assertIsArray($showAllCategoriesUseCaseResponse->categories());
        self::assertCount(2, $showAllCategoriesUseCaseResponse->categories());
    }

    public function testDeveSerCapazDeRetornarUmJsonSerializado(): void
    {
        $categoryName1 = 'Categoria 1';
        $categoryIcon1 = 'NomeDoIcone1';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName1, $categoryIcon1);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryName2 = 'Categoria 2';
        $categoryIcon2 = 'NomeDoIcone2';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName2, $categoryIcon2);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $showAllCategoriesUseCaseResponse = $this->showAllCategoriesUseCase->execute();

        $categories = [];

        foreach ($showAllCategoriesUseCaseResponse->categories() as $category) {
            $categories[] = $category->toArray();
        }

        $expectedJsonSerialize = json_encode($categories);

        self::assertEquals($expectedJsonSerialize, json_encode($showAllCategoriesUseCaseResponse));
    }
}
