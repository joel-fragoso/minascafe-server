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
        $categoryId = $showOneCategoryUseCaseRequest->categoryId();

        $findCategory = $this->categoryRepository->findById(
            new CategoryId($categoryId)
        );

        if (!$findCategory) {
            throw new CategoryNotFoundException("A categoria '{$categoryId}' não foi encontrada");
        }

        return new ShowOneCategoryUseCaseResponse(
            $findCategory->id()->value(),
            $findCategory->name()->value(),
            $findCategory->icon()->value(),
            $findCategory->isActive()->value()
        );
    }
}
