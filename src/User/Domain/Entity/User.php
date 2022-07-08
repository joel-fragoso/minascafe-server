<?php

declare(strict_types=1);

namespace Minascafe\User\Domain\Entity;

use Minascafe\User\Domain\ValueObject\UserActive;
use Minascafe\User\Domain\ValueObject\UserAvatar;
use Minascafe\User\Domain\ValueObject\UserCreatedAt;
use Minascafe\User\Domain\ValueObject\UserEmail;
use Minascafe\User\Domain\ValueObject\UserId;
use Minascafe\User\Domain\ValueObject\UserName;
use Minascafe\User\Domain\ValueObject\UserPassword;
use Minascafe\User\Domain\ValueObject\UserUpdatedAt;

final class User
{
    private function __construct(
        private UserId $id,
        private UserName $name,
        private UserEmail $email,
        private UserPassword $password,
        private UserAvatar $avatar,
        private UserActive $active,
        private UserCreatedAt $createdAt,
        private UserUpdatedAt $updatedAt
    ) {
    }

    public static function create(
        UserId $id,
        UserName $name,
        UserEmail $email,
        UserPassword $password,
        UserAvatar $avatar,
        UserActive $active,
        UserCreatedAt $createdAt,
        UserUpdatedAt $updatedAt
    ): self {
        return new self($id, $name, $email, $password, $avatar, $active, $createdAt, $updatedAt);
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): UserName
    {
        return $this->name;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function password(): UserPassword
    {
        return $this->password;
    }

    public function avatar(): UserAvatar
    {
        return $this->avatar;
    }

    public function isActive(): UserActive
    {
        return $this->active;
    }

    public function createdAt(): UserCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): UserUpdatedAt
    {
        return $this->updatedAt;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            new UserId($data['id']),
            new UserName($data['name']),
            new UserEmail($data['email']),
            new UserPassword($data['password']),
            new UserAvatar($data['avatar']),
            new UserActive($data['active']),
            new UserCreatedAt($data['createdA']),
            new UserUpdatedAt($data['updatedAt'])
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id()->value(),
            'name' => $this->name()->value(),
            'email' => $this->email()->value(),
            'password' => $this->password()->value(),
            'avatar' => $this->avatar()->value(),
            'active' => $this->isActive()->value(),
            'createdAt' => $this->createdAt()->value(),
            'updatedAt' => $this->updatedAt()->value(),
        ];
    }
}
