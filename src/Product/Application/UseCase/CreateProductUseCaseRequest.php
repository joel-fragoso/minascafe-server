<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

final class CreateProductUseCaseRequest
{
    public function __construct(private string $categoryId, private string $name)
    {
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
