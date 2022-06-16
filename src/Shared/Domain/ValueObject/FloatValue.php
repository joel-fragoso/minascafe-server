<?php

declare(strict_types=1);

namespace Minascafe\Shared\Domain\ValueObject;

class FloatValue
{
    public function __construct(private float $value)
    {
    }

    public function value(): float
    {
        return $this->value;
    }
}
