<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

use JsonSerializable;
use Minascafe\Category\Domain\Entity\Category;

final class ShowOneProductUseCaseResponse implements JsonSerializable
{
    public function __construct(
        private string $productId,
        private Category $category,
        private string $name,
        private float $price
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

    /**
     * @return array<string, string|float|array<string, string>>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->productId(),
            'name' => $this->name(),
            'price' => $this->price(),
            'category' => $this->category()->toArray(),
        ];
    }
}
