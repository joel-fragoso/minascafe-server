<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

use JsonSerializable;
use Minascafe\Category\Domain\Entity\Category;

final class CreateProductUseCaseResponse implements JsonSerializable
{
    public function __construct(
        private string $productId,
        private Category $category,
        private string $name,
        private float $price,
        private bool $active
    ) {
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function category(): Category
    {
        return $this->category;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return array<string, string|float|bool|array<string, string>>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->productId(),
            'name' => $this->name(),
            'price' => $this->price(),
            'active' => $this->isActive(),
            'category' => $this->category()->toArray(),
        ];
    }
}
