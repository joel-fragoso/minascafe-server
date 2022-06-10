<?php

declare(strict_types=1);

namespace Minascafe\Tests\Category\Application\UseCase;

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
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId = $createCategoryUseCaseResponse->categoryId();
        $categoryName = 'Categoria 2';

        $updateCategoryUseCaseRequest = new UpdateCategoryUseCaseRequest($categoryId, $categoryName);

        $updateCategoryUseCaseResponse = $this->updateCategoryUseCase->execute($updateCategoryUseCaseRequest);

        self::assertEquals($categoryId, $updateCategoryUseCaseResponse->categoryId());
        self::assertEquals($categoryName, $updateCategoryUseCaseResponse->name());
    }

    public function testNaoDeveSerCapazDeAtualizarUmaCategoriaQueNaoExiste(): void
    {
        self::expectException(CategoryNotFoundException::class);

        $categoryId = '00000000-0000-0000-0000-000000000000';
        $categoryName = 'Categoria';

        $updateCategoryUseCaseRequest = new UpdateCategoryUseCaseRequest($categoryId, $categoryName);

        $this->updateCategoryUseCase->execute($updateCategoryUseCaseRequest);
    }

    public function testDeveSerCapazDeRetornarUmJsonSerializado(): void
    {
        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest('Categoria');

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        $categoryId = $createCategoryUseCaseResponse->categoryId();
        $categoryName = 'Categoria 2';

        $updateCategoryUseCaseRequest = new UpdateCategoryUseCaseRequest($categoryId, $categoryName);

        $updateCategoryUseCaseResponse = $this->updateCategoryUseCase->execute($updateCategoryUseCaseRequest);

        $expectedJsonSerialize = json_encode([
            'id' => $updateCategoryUseCaseResponse->categoryId(),
            'name' => $updateCategoryUseCaseResponse->name(),
        ]);

        self::assertEquals($expectedJsonSerialize, json_encode($updateCategoryUseCaseResponse));
    }
}
