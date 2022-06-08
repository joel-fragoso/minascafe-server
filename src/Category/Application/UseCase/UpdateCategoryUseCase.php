<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;

final class UpdateCategoryUseCase
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function execute(UpdateCategoryUseCaseRequest $updateCategoryUseCaseRequest): UpdateCategoryUseCaseResponse
    {
        $categoryId = $updateCategoryUseCaseRequest->categoryId();
        $categoryName = $updateCategoryUseCaseRequest->name();

        $findCategory = $this->categoryRepository->findById(new CategoryId($categoryId));

        if (!$findCategory) {
            throw new CategoryNotFoundException("A categoria '{$categoryId}' não foi encontrada");
        }

        $category = Category::create(new CategoryId($findCategory->id()->value()), new CategoryName($categoryName));

        $this->categoryRepository->update($category);

        return new UpdateCategoryUseCaseResponse($category->id()->value(), $category->name()->value());
    }
}
