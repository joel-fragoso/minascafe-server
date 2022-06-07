<?php

declare(strict_types=1);

namespace Minascafe\Shared\Domain\ValueObject;

class IdValue
{
    public function __construct(private ?int $id = null)
    {
    }

    public function value(): ?int
    {
        return $this->id;
    }
}
