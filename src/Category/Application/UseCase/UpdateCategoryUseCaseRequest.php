<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

final class UpdateCategoryUseCaseRequest
{
    public function __construct(
        private string $categoryId,
        private string $name,
        private string $icon,
        private bool $active
    ) {
    }

    public function categoryId(): string
    {
        return $this->categoryId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function icon(): string
    {
        return $this->icon;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
