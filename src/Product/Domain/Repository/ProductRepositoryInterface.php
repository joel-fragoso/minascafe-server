<?php

declare(strict_types=1);

namespace Minascafe\Product\Domain\Repository;

use Minascafe\Product\Domain\Entity\Product;
use Minascafe\Product\Domain\ValueObject\ProductId;
use Minascafe\Product\Domain\ValueObject\ProductName;

interface ProductRepositoryInterface
{
    /**
     * @return Product[]
     */
    public function findAll(
        ?int $active = null,
        ?string $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array;

    public function findById(ProductId $productId): ?Product;

    public function findByName(ProductName $productName): ?Product;

    public function create(Product $product): void;

    public function update(Product $product): void;

    public function delete(Product $product): void;
}
