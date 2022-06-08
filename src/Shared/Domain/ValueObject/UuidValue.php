<?php

declare(strict_types=1);

namespace Minascafe\Shared\Domain\ValueObject;

use Minascafe\Shared\Domain\Adapter\UuidAdapterInterface;
use Minascafe\Shared\Domain\Exception\InvalidUuidException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UuidValue implements UuidAdapterInterface
{
    /**
     * @throws InvalidUuidException
     */
    public function __construct(private string $value)
    {
        if (!self::isValid($value)) {
            throw new InvalidUuidException("O id '{$value}' não é válido");
        }

        $this->value = $value;
    }

    public static function generate(): string
    {
        return RamseyUuid::uuid4()->toString();
    }

    public static function isValid(string $value): bool
    {
        return RamseyUuid::isValid($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
