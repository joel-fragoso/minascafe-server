<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

use JsonSerializable;
use Minascafe\Product\Domain\Entity\Product;

final class ShowAllProductsUseCaseResponse implements JsonSerializable
{
    /**
     * @param Product[] $products
     */
    public function __construct(private array $products = [])
    {
    }

    /**
     * @return Product[]
     */
    public function products(): array
    {
        return $this->products;
    }

    /**
     * @return array<int, mixed>
     */
    public function jsonSerialize(): array
    {
        $products = [];

        foreach ($this->products as $product) {
            $products[] = $product->toArray();
        }

        return $products;
    }
}
