<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use JsonSerializable;
use Minascafe\User\Domain\Entity\User;

final class ShowAllUsersUseCaseResponse implements JsonSerializable
{
    /**
     * @param User[] $users
     */
    public function __construct(private array $users = [])
    {
    }

    /**
     * @return User[]
     */
    public function users(): array
    {
        return $this->users;
    }

    /**
     * @return array<int, mixed>
     */
    public function jsonSerialize(): mixed
    {
        $users = [];

        foreach ($this->users as $user) {
            $userData = $user->toArray();
            unset($userData['password']);

            $users[] = $userData;
        }

        return $users;
    }
}
