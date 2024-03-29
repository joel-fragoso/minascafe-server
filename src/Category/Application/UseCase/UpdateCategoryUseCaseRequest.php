<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

final class UpdateCategoryUseCaseRequest
{
    public function __construct(
        private string $categoryId,
        private ?string $name = null,
        private ?string $icon = null,
        private ?bool $active = null
    ) {
    }

    public function categoryId(): string
    {
        return $this->categoryId;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function icon(): ?string
    {
        return $this->icon;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }
}
