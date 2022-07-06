<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use DateTimeImmutable;
use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Domain\ValueObject\CategoryActive;
use Minascafe\Category\Domain\ValueObject\CategoryCreatedAt;
use Minascafe\Category\Domain\ValueObject\CategoryIcon;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;
use Minascafe\Category\Domain\ValueObject\CategoryUpdatedAt;
use Minascafe\Shared\Application\Adapter\CacheAdapterInterface;

final class UpdateCategoryUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private CacheAdapterInterface $cacheAdapter,
    ) {
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function execute(UpdateCategoryUseCaseRequest $updateCategoryUseCaseRequest): UpdateCategoryUseCaseResponse
    {
        $categoryId = $updateCategoryUseCaseRequest->categoryId();
        $categoryName = $updateCategoryUseCaseRequest->name();
        $categoryIcon = $updateCategoryUseCaseRequest->icon();
        $categoryActive = $updateCategoryUseCaseRequest->isActive();

        $findCategory = $this->categoryRepository->findById(new CategoryId($categoryId));

        if (!$findCategory) {
            throw new CategoryNotFoundException("A categoria '{$categoryId}' não foi encontrada");
        }

        $category = Category::create(
            new CategoryId($findCategory->id()->value()),
            new CategoryName($categoryName ?? $findCategory->name()->value()),
            new CategoryIcon($categoryIcon ?? $findCategory->icon()->value()),
            new CategoryActive($categoryActive ?? $findCategory->isActive()->value()),
            new CategoryCreatedAt($findCategory->createdAt()->value()),
            new CategoryUpdatedAt(new DateTimeImmutable())
        );

        $this->categoryRepository->update($category);

        $this->cacheAdapter->delete('show-all-categories');

        return new UpdateCategoryUseCaseResponse(
            $category->id()->value(),
            $category->name()->value(),
            $category->icon()->value(),
            $category->isActive()->value(),
            $category->createdAt()->value(),
            $category->updatedAt()->value()
        );
    }
}
