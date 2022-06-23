<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;

final class ShowAllCategoriesUseCase
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function execute(
        ShowAllCategoriesUseCaseRequest $showAllCategoriesUseCaseRequest
    ): ShowAllCategoriesUseCaseResponse {
        $categories = $this->categoryRepository->findAll(
            $showAllCategoriesUseCaseRequest->active(),
            $showAllCategoriesUseCaseRequest->order(),
            $showAllCategoriesUseCaseRequest->limit(),
            $showAllCategoriesUseCaseRequest->offset()
        );

        return new ShowAllCategoriesUseCaseResponse($categories);
    }
}
