<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

final class CreateCategoryUseCaseRequest
{
    public function __construct(private string $name, private string $icon)
    {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function icon(): string
    {
        return $this->icon;
    }
}
