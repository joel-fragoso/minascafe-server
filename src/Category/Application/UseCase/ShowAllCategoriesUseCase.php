<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;

final class ShowAllCategoriesUseCase
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    /**
     * @return Category[]
     */
    public function execute(): array
    {
        return $this->categoryRepository->all();
    }
}
