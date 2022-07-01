<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use JsonSerializable;
use Minascafe\User\Domain\Entity\User;

final class AuthenticateUserUseCaseResponse implements JsonSerializable
{
    public function __construct(private User $user, private string $token)
    {
    }

    public function user(): User
    {
        return $this->user;
    }

    public function token(): string
    {
        return $this->token;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $userData = $this->user()->toArray();
        unset($userData['password']);

        return [
            'user' => $userData,
            'token' => $this->token(),
        ];
    }
}
