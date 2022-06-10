<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

final class UpdateProductUseCaseRequest
{
    public function __construct(private string $productId, private string $categoryId, private string $name)
    {
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function categoryId(): string
    {
        return $this->categoryId;
    }

    public function name(): string
    {
        return $this->name;
    }
}
