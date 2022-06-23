<?php

declare(strict_types=1);

namespace Minascafe\Shared\Domain\ValueObject;

class BooleanValue
{
    public function __construct(private bool $value)
    {
    }

    public function value(): bool
    {
        return $this->value;
    }
}
