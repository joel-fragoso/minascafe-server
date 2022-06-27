<?php

declare(strict_types=1);

namespace Minascafe\Tests\Category\Application\UseCase;

use DateTimeImmutable;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Application\UseCase\UpdateCategoryUseCase;
use Minascafe\Category\Application\UseCase\UpdateCategoryUseCaseRequest;
use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Infrastructure\Persistence\InMemory\Repository\CategoryRepository;
use PHPUnit\Framework\TestCase;

final class UpdateCategoryUseCaseTest extends TestCase
{
    private CreateCategoryUseCase $createCategoryUseCase;

    private UpdateCategoryUseCase $updateCategoryUseCase;

    protected function setUp(): void
    {
        $inMemoryCategoryRepository = new CategoryRepository();
        $this->createCategoryUseCase = new CreateCategoryUseCase($inMemoryCategoryRepository);
        $this->updateCategoryUseCase = new UpdateCategoryUseCase($inMemoryCategoryRepository);
    }

    public function testDeveSerCapazDeAtualizarUmaCategoria(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId = $createCategoryUseCaseResponse->categoryId();
        $categoryName = 'Categoria 2';
        $categoryIcon = 'NomeDoIcone2';

        $updateCategoryUseCaseRequest = new UpdateCategoryUseCaseRequest(
            $categoryId,
            $categoryName,
            $categoryIcon,
            true,
        );

        $updateCategoryUseCaseResponse = $this->updateCategoryUseCase->execute($updateCategoryUseCaseRequest);

        self::assertEquals($categoryId, $updateCategoryUseCaseResponse->categoryId());
        self::assertEquals($categoryName, $updateCategoryUseCaseResponse->name());
        self::assertEquals($categoryIcon, $updateCategoryUseCaseResponse->icon());
        self::assertEquals($createCategoryUseCaseResponse->createdAt(), $updateCategoryUseCaseResponse->createdAt());
    }

    public function testNaoDeveSerCapazDeAtualizarUmaCategoriaQueNaoExiste(): void
    {
        self::expectException(CategoryNotFoundException::class);

        $updateCategoryUseCaseRequest = new UpdateCategoryUseCaseRequest(
            '00000000-0000-0000-0000-000000000000',
            'Categoria',
            'NomeDoIcone',
            true
        );

        $this->updateCategoryUseCase->execute($updateCategoryUseCaseRequest);
    }

    public function testDeveSerCapazDeRetornarUmJsonSerializado(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria', 'NomeDoIcone');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $updateCategoryUseCaseRequest = new UpdateCategoryUseCaseRequest(
            $createCategoryUseCaseResponse->categoryId(),
            'Categoria 2',
            'NomeDoIcone2',
            true
        );

        $updateCategoryUseCaseResponse = $this->updateCategoryUseCase->execute($updateCategoryUseCaseRequest);

        $expectedJsonSerialize = json_encode([
            'id' => $updateCategoryUseCaseResponse->categoryId(),
            'name' => $updateCategoryUseCaseResponse->name(),
            'icon' => $updateCategoryUseCaseResponse->icon(),
            'active' => $updateCategoryUseCaseResponse->isActive(),
            'createdAt' => $updateCategoryUseCaseResponse->createdAt()->format(DateTimeImmutable::ATOM),
        ]);

        self::assertEquals($expectedJsonSerialize, json_encode($updateCategoryUseCaseResponse));
    }
}
