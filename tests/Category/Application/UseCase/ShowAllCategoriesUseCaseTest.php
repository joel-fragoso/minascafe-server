<?php

declare(strict_types=1);

namespace Minascafe\Tests\Category\Application\UseCase;

use DateTimeImmutable;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Application\UseCase\ShowAllCategoriesUseCase;
use Minascafe\Category\Application\UseCase\ShowAllCategoriesUseCaseRequest;
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
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria 1', 'NomeDoIcone1');

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria 2', 'NomeDoIcone2');

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $showAllCategoriesUseCaseRequest = new ShowAllCategoriesUseCaseRequest(order: 'name');

        $showAllCategoriesUseCaseResponse = $this->showAllCategoriesUseCase->execute($showAllCategoriesUseCaseRequest);

        self::assertIsArray($showAllCategoriesUseCaseResponse->categories());
        self::assertCount(2, $showAllCategoriesUseCaseResponse->categories());
    }

    public function testDeveSerCapazDeListarTodasAsCategoriasAtivas(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria 1', 'NomeDoIcone1', false);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria 2', 'NomeDoIcone2', true);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $showAllCategoriesUseCaseRequest = new ShowAllCategoriesUseCaseRequest(active: 1, order: 'name');

        $showAllCategoriesUseCaseResponse = $this->showAllCategoriesUseCase->execute($showAllCategoriesUseCaseRequest);

        self::assertIsArray($showAllCategoriesUseCaseResponse->categories());
        self::assertCount(1, $showAllCategoriesUseCaseResponse->categories());
    }

    public function testDeveSerCapazDeListarTodasAsCategoriasInativas(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria 1', 'NomeDoIcone1', false);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria 2', 'NomeDoIcone2', true);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $showAllCategoriesUseCaseRequest = new ShowAllCategoriesUseCaseRequest(active: 0, order: 'name');

        $showAllCategoriesUseCaseResponse = $this->showAllCategoriesUseCase->execute($showAllCategoriesUseCaseRequest);

        self::assertIsArray($showAllCategoriesUseCaseResponse->categories());
        self::assertCount(1, $showAllCategoriesUseCaseResponse->categories());
    }

    public function testDeveSerCapazDeRetornarUmJsonSerializado(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria 1', 'NomeDoIcone1', false);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria 2', 'NomeDoIcone2', true);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $showAllCategoriesUseCaseRequest = new ShowAllCategoriesUseCaseRequest(order: 'name');

        $showAllCategoriesUseCaseResponse = $this->showAllCategoriesUseCase->execute($showAllCategoriesUseCaseRequest);

        $categories = [];

        foreach ($showAllCategoriesUseCaseResponse->categories() as $category) {
            $categories[] = [
                ...$category->toArray(),
                'createdAt' => $category->toArray()['createdAt']->format(DateTimeImmutable::ATOM),
            ];
        }

        $expectedJsonSerialize = json_encode($categories);

        self::assertEquals($expectedJsonSerialize, json_encode($showAllCategoriesUseCaseResponse));
    }
}
