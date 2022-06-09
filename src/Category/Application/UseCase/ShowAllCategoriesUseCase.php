<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;

final class ShowAllCategoriesUseCase
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function execute(): ShowAllCategoriesUseCaseResponse
    {
        $categories = $this->categoryRepository->findAllCategories();

        return new ShowAllCategoriesUseCaseResponse($categories);
    }
}
