<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

final class CreateCategoryUseCaseRequest
{
    public function __construct(private string $name)
    {
    }

    public function name(): string
    {
        return $this->name;
    }
}
