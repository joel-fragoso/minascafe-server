<?php

declare(strict_types=1);

namespace Minascafe\Shared\Domain\ValueObject;

use DateTimeInterface;

class DateTimeValue
{
    public function __construct(private DateTimeInterface $value)
    {
    }

    public function value(): DateTimeInterface
    {
        return $this->value;
    }
}
