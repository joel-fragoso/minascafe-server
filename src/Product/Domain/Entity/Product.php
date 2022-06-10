<?php

declare(strict_types=1);

namespace Minascafe\Product\Domain\Entity;

use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Product\Domain\ValueObject\ProductId;
use Minascafe\Product\Domain\ValueObject\ProductName;

final class Product
{
    private function __construct(private ProductId $id, private Category $category, private ProductName $name)
    {
    }

    public static function create(ProductId $id, Category $category, ProductName $name): self
    {
        return new self($id, $category, $name);
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

    /**
     * @param array<string, string|array<string, string>> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            new ProductId($data['id']),
            Category::fromArray($data['category']),
            new ProductName($data['name'])
        );
    }

    /**
     * @return array<string, string|array<string, string>>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id()->value(),
            'category' => $this->category()->toArray(),
            'name' => $this->name()->value(),
        ];
    }
}
