<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use JsonSerializable;
use Minascafe\Category\Domain\Entity\Category;

final class ShowAllCategoriesUseCaseResponse implements JsonSerializable
{
    /**
     * @param Category[] $categories
     */
    public function __construct(private array $categories = [])
    {
    }

    /**
     * @return Category[]
     */
    public function categories(): array
    {
        return $this->categories;
    }

    /**
     * @return array<int, mixed>
     */
    public function jsonSerialize(): mixed
    {
        $categories = [];

        foreach ($this->categories as $category) {
            $categories[] = $category->toArray();
        }

        return $categories;
    }
}
