<?php

declare(strict_types=1);

namespace Minascafe\User\Domain\Exception;

use Minascafe\Shared\Domain\Exception\DomainException;
use Throwable;

final class DuplicatedUserException extends DomainException
{
    public function __construct(string $message, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
