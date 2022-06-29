<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use DateTimeImmutable;
use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Domain\Exception\DuplicateCategoryException;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Domain\ValueObject\CategoryActive;
use Minascafe\Category\Domain\ValueObject\CategoryCreatedAt;
use Minascafe\Category\Domain\ValueObject\CategoryIcon;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;
use Minascafe\Category\Domain\ValueObject\CategoryUpdatedAt;

final class CreateCategoryUseCase
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    /**
     * @throws DuplicateCategoryException
     */
    public function execute(CreateCategoryUseCaseRequest $createCategoryUseCaseRequest): CreateCategoryUseCaseResponse
    {
        $categoryName = $createCategoryUseCaseRequest->name();

        $findCategory = $this->categoryRepository->findByName(
            new CategoryName($categoryName)
        );

        if ($findCategory) {
            throw new DuplicateCategoryException("A categoria '{$categoryName}' já existe");
        }

        $category = Category::create(
            new CategoryId(CategoryId::generate()),
            new CategoryName($categoryName),
            new CategoryIcon($createCategoryUseCaseRequest->icon()),
            new CategoryActive($createCategoryUseCaseRequest->isActive() ?? true),
            new CategoryCreatedAt(new DateTimeImmutable()),
            new CategoryUpdatedAt(null)
        );

        $this->categoryRepository->create($category);

        return new CreateCategoryUseCaseResponse(
            $category->id()->value(),
            $category->name()->value(),
            $category->icon()->value(),
            $category->isActive()->value(),
            $category->createdAt()->value(),
            $category->updatedAt()->value()
        );
    }
}
