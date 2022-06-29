<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

final class CreateCategoryUseCaseRequest
{
    public function __construct(
        private string $name,
        private string $icon,
        private ?bool $active = null
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function icon(): string
    {
        return $this->icon;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }
}
