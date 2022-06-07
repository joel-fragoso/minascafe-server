<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Domain\ValueObject\CategoryId;

final class ShowOneCategoryUseCase
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function execute(ShowOneCategoryUseCaseRequest $showOneCategoryUseCaseRequest): ShowOneCategoryUseCaseResponse
    {
        $findCategory = $this->categoryRepository->findById(
            new CategoryId($showOneCategoryUseCaseRequest->id())
        );

        if (!$findCategory) {
            throw new CategoryNotFoundException("A categoria com id '{$showOneCategoryUseCaseRequest->id()}' não foi encontrada");
        }

        return new ShowOneCategoryUseCaseResponse(
            $findCategory->id()->value(),
            $findCategory->name()->value()
        );
    }
}
