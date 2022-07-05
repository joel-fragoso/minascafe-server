<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Persistence\Doctrine\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Minascafe\User\Infrastructure\Persistence\Doctrine\Repository\UserTokenRepository;

#[Entity(repositoryClass: UserTokenRepository::class), Table(name: 'user_tokens')]
final class UserToken
{
    public function __construct(
        #[Id, Column(type: 'guid', length: 36)]
        private string $id,
        #[ManyToOne(targetEntity: User::class, fetch: 'EAGER'), JoinColumn(name: 'userId', referencedColumnName: 'id', nullable: false)]
        private User $user,
        #[Column(type: 'guid', length: 36)]
        private string $token,
        #[Column(type: 'datetime')]
        private DateTimeInterface $createdAt,
        #[Column(type: 'datetime', nullable: true)]
        private DateTimeInterface|null $updatedAt,
    ) {
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(DateTimeInterface|null $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt(): DateTimeInterface|null
    {
        return $this->updatedAt;
    }
}
