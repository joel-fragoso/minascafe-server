<?php

declare(strict_types=1);

namespace Minascafe\Tests\Category\Application\UseCase;

use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Domain\Exception\DuplicateCategoryException;
use Minascafe\Category\Infrastructure\Persistence\InMemory\Repository\CategoryRepository;
use PHPUnit\Framework\TestCase;

final class CreateCategoryUseCaseTest extends TestCase
{
    private CreateCategoryUseCase $createCategoryUseCase;

    protected function setUp(): void
    {
        $inMemoryCategoryRepository = new CategoryRepository();
        $this->createCategoryUseCase = new CreateCategoryUseCase($inMemoryCategoryRepository);
    }

    public function testDeveSerCapazDeCriarUmaCategoria(): void
    {
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon, true);

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        self::assertNotNull($createCategoryUseCaseResponse->categoryId());
        self::assertEquals($categoryName, $createCategoryUseCaseResponse->name());
        self::assertEquals($categoryIcon, $createCategoryUseCaseResponse->icon());
        self::assertTrue($createCategoryUseCaseResponse->isActive());
    }

    public function testNaoDeveSerCapazDeCriarUmaCategoriaComOMesmoNome(): void
    {
        self::expectException(DuplicateCategoryException::class);

        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon, true);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);
    }

    public function testDeveSerCapazDeRetornarUmJsonSerializado(): void
    {
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon, true);

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $expectedJsonSerialize = json_encode([
            'id' => $createCategoryUseCaseResponse->categoryId(),
            'name' => $createCategoryUseCaseResponse->name(),
            'icon' => $createCategoryUseCaseResponse->icon(),
            'active' => $createCategoryUseCaseResponse->isActive(),
            'updatedAt' => $createCategoryUseCaseResponse->updatedAt(),
        ]);

        self::assertEquals($expectedJsonSerialize, json_encode($createCategoryUseCaseResponse));
    }
}
