<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

final class CreateProductUseCaseRequest
{
    public function __construct(
        private string $categoryId,
        private string $name,
        private float $price,
        private ?bool $active = null
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

    public function price(): float
    {
        return $this->price;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }
}
