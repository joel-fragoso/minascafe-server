<?php

declare(strict_types=1);

namespace Minascafe\Category\Domain\Repository;

use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;

interface CategoryRepositoryInterface
{
    /**
     * @return Category[]
     */
    public function findAll(
        ?int $active = null,
        ?string $order = null,
        ?int $limit = null,
        ?int $offset = null
    ): array;

    public function findById(CategoryId $categoryId): ?Category;

    public function findByName(CategoryName $categoryName): ?Category;

    public function create(Category $category): void;

    public function update(Category $category): void;

    public function delete(Category $category): void;
}
