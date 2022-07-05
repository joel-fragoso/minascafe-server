<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

final class SendForgotPasswordUseCaseRequest
{
    public function __construct(private string $email)
    {
    }

    public function email(): string
    {
        return $this->email;
    }
}
