<?php

declare(strict_types=1);

namespace Minascafe\Tests\Shared\Domain\ValueObject;

use Minascafe\Shared\Domain\Exception\InvalidUuidException;
use Minascafe\Shared\Domain\ValueObject\UuidValue;
use PHPUnit\Framework\TestCase;

final class UuidValueTest extends TestCase
{
    public function testNaoDeveSerCapazDeAdicionarUmUuidInvalido(): void
    {
        self::expectException(InvalidUuidException::class);

        $uuid = '00000000-0000-0000-0000-00000000000';

        new UuidValue($uuid);
    }
}
