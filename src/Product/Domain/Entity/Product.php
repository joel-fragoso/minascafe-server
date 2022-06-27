<?php

declare(strict_types=1);

namespace Minascafe\Product\Domain\Entity;

use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Product\Domain\ValueObject\ProductActive;
use Minascafe\Product\Domain\ValueObject\ProductCreatedAt;
use Minascafe\Product\Domain\ValueObject\ProductId;
use Minascafe\Product\Domain\ValueObject\ProductName;
use Minascafe\Product\Domain\ValueObject\ProductPrice;

final class Product
{
    private function __construct(
        private ProductId $id,
        private Category $category,
        private ProductName $name,
        private ProductPrice $price,
        private ProductActive $active,
        private ProductCreatedAt $createdAt
    ) {
    }

    public static function create(
        ProductId $id,
        Category $category,
        ProductName $name,
        ProductPrice $price,
        ProductActive $active,
        ProductCreatedAt $createdAt
    ): self {
        return new self($id, $category, $name, $price, $active, $createdAt);
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function category(): Category
    {
        return $this->category;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public function price(): ProductPrice
    {
        return $this->price;
    }

    public function isActive(): ProductActive
    {
        return $this->active;
    }

    public function createdAt(): ProductCreatedAt
    {
        return $this->createdAt;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            new ProductId($data['id']),
            Category::fromArray($data['category']),
            new ProductName($data['name']),
            new ProductPrice($data['price']),
            new ProductActive($data['active']),
            new ProductCreatedAt($data['createdAt'])
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id()->value(),
            'category' => $this->category()->toArray(),
            'name' => $this->name()->value(),
            'price' => $this->price()->value(),
            'active' => $this->isActive()->value(),
            'createdAt' => $this->createdAt()->value(),
        ];
    }
}
