<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

final class ShowOneCategoryUseCaseRequest
{
    public function __construct(private int $id)
    {
    }

    public function id(): int
    {
        return $this->id;
    }
}
