<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

final class DeleteCategoryUseCaseRequest
{
    public function __construct(private string $categoryId)
    {
    }

    public function categoryId(): string
    {
        return $this->categoryId;
    }
}
