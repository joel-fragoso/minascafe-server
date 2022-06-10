<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

final class ShowOneProductUseCaseRequest
{
    public function __construct(private string $productId)
    {
    }

    public function productId(): string
    {
        return $this->productId;
    }
}
