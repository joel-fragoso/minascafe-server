<?php

declare(strict_types=1);

namespace Minascafe\Product\Infrastructure\Persistence\InMemory\Repository;

use Minascafe\Product\Domain\Entity\Product;
use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;
use Minascafe\Product\Domain\ValueObject\ProductId;
use Minascafe\Product\Domain\ValueObject\ProductName;

final class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @param Product[] $products
     */
    public function __construct(private array $products = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public function findAllProducts(): array
    {
        return $this->products;
    }

    public function findById(ProductId $productId): ?Product
    {
        foreach ($this->products as $product) {
            if ($product->id()->value() === $productId->value()) {
                return $product;
            }
        }

        return null;
    }

    public function findByName(ProductName $productName): ?Product
    {
        foreach ($this->products as $product) {
            if ($product->name()->value() === $productName->value()) {
                return $product;
            }
        }

        return null;
    }

    public function create(Product $product): void
    {
        $this->products[] = $product;
    }

    public function update(Product $product): void
    {
        foreach ($this->products as $key => $prod) {
            if (null !== $product->id()->value() && $product->id()->value() === $prod->id()->value()) {
                $this->products[$key] = $product;
            }
        }
    }

    public function delete(Product $product): void
    {
        foreach ($this->products as $key => $prod) {
            if ($product->id()->value() === $prod->id()->value()) {
                unset($this->products[$key]);
            }
        }
    }
}
