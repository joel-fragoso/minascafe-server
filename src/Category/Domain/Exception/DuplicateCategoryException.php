<?php

declare(strict_types=1);

namespace Minascafe\Category\Domain\Exception;

use Minascafe\Shared\Domain\Exception\DomainException;
use Throwable;

final class DuplicateCategoryException extends DomainException
{
    public function __construct(string $message, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
