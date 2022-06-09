<?php

declare(strict_types=1);

namespace Minascafe\Category\Infrastructure\Persistence\InMemory\Repository;

use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;

final class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @param Category[] $categories
     */
    public function __construct(private array $categories = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public function findAllCategories(): array
    {
        return $this->categories;
    }

    public function findById(CategoryId $categoryId): ?Category
    {
        foreach ($this->categories as $category) {
            if ($category->id()->value() === $categoryId->value()) {
                return $category;
            }
        }

        return null;
    }

    public function findByName(CategoryName $categoryName): ?Category
    {
        foreach ($this->categories as $category) {
            if ($category->name()->value() === $categoryName->value()) {
                return $category;
            }
        }

        return null;
    }

    public function create(Category $category): void
    {
        $this->categories[] = $category;
    }

    public function update(Category $category): void
    {
        foreach ($this->categories as $key => $cat) {
            if (null !== $category->id()->value() && $category->id()->value() === $cat->id()->value()) {
                $this->categories[$key] = $category;
            }
        }
    }

    public function delete(Category $category): void
    {
        foreach ($this->categories as $key => $cat) {
            if ($category->id()->value() === $cat->id()->value()) {
                unset($this->categories[$key]);
            }
        }
    }
}
