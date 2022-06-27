<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

final class UpdateProductUseCaseRequest
{
    public function __construct(
        private string $productId,
        private ?string $categoryId = null,
        private ?string $name = null,
        private ?float $price = null,
        private ?bool $active = null
    ) {
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function categoryId(): ?string
    {
        return $this->categoryId;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function price(): ?float
    {
        return $this->price;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }
}
