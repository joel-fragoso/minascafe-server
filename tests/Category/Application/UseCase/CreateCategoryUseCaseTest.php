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

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName);

        $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        self::assertEquals($categoryName, $createCategoryUseCaseResponse->name());
    }

    public function testNaoDeveSerCapazDeCriarUmaCategoriaComOMesmoNome(): void
    {
        $categoryName = 'Categoria';

        $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($categoryName);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

        self::expectException(DuplicateCategoryException::class);

        $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);
    }
}
