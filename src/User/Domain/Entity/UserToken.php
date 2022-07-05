<?php

declare(strict_types=1);

namespace Minascafe\User\Domain\Entity;

use Minascafe\User\Domain\ValueObject\UserTokenCreatedAt;
use Minascafe\User\Domain\ValueObject\UserTokenId;
use Minascafe\User\Domain\ValueObject\UserTokenToken;
use Minascafe\User\Domain\ValueObject\UserTokenUpdatedAt;

final class UserToken
{
    private function __construct(
        private UserTokenId $id,
        private User $user,
        private UserTokenToken $token,
        private UserTokenCreatedAt $createdAt,
        private UserTokenUpdatedAt $updatedAt
    ) {
    }

    public static function create(
        UserTokenId $id,
        User $user,
        UserTokenToken $token,
        UserTokenCreatedAt $createdAt,
        UserTokenUpdatedAt $updatedAt
    ): self {
        return new self($id, $user, $token, $createdAt, $updatedAt);
    }

    public function id(): UserTokenId
    {
        return $this->id;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function token(): UserTokenToken
    {
        return $this->token;
    }

    public function createdAt(): UserTokenCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): UserTokenUpdatedAt
    {
        return $this->updatedAt;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            new UserTokenId($data['id']),
            User::fromArray($data['user']),
            new UserTokenToken($data['token']),
            new UserTokenCreatedAt($data['createdAt']),
            new UserTokenUpdatedAt($data['updatedAt'])
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'user' => $this->user(),
            'token' => $this->token(),
            'createdAt' => $this->createdAt(),
            'updatedAt' => $this->updatedAt(),
        ];
    }
}
