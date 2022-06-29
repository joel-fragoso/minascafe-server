<?php

declare(strict_types=1);

namespace Minascafe\Tests\Category\Application\UseCase;

use DateTimeInterface;
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

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName, $categoryIcon);

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        self::assertNotNull($createCategoryUseCaseResponse->categoryId());
        self::assertEquals($categoryName, $createCategoryUseCaseResponse->name());
        self::assertEquals($categoryIcon, $createCategoryUseCaseResponse->icon());
        self::assertTrue($createCategoryUseCaseResponse->isActive());
        self::assertInstanceOf(DateTimeInterface::class, $createCategoryUseCaseResponse->createdAt());
    }

    public function testNaoDeveSerCapazDeCriarUmaCategoriaComOMesmoNome(): void
    {
        self::expectException(DuplicateCategoryException::class);

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);
    }

    public function testDeveSerCapazDeRetornarUmJsonSerializado(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $expectedJsonSerialize = json_encode([
            'id' => $createCategoryUseCaseResponse->categoryId(),
            'name' => $createCategoryUseCaseResponse->name(),
            'icon' => $createCategoryUseCaseResponse->icon(),
            'active' => $createCategoryUseCaseResponse->isActive(),
            'createdAt' => $createCategoryUseCaseResponse->createdAt(),
            'updatedAt' => $createCategoryUseCaseResponse->updatedAt(),
        ]);

        self::assertEquals($expectedJsonSerialize, json_encode($createCategoryUseCaseResponse));
    }
}
