<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Persistence\Doctrine\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Minascafe\User\Infrastructure\Persistence\Doctrine\Repository\UserRepository;

#[Entity(repositoryClass: UserRepository::class), Table(name: 'users')]
final class User
{
    #[Id, Column(type: 'guid', length: 36)]
    private string $id;

    #[Column(type: 'string', length: 45)]
    private string $name;

    #[Column(type: 'string', unique: true, length: 60)]
    private string $email;

    #[Column(type: 'string', length: 60)]
    private string $password;

    #[Column(type: 'string', nullable: true)]
    private string|null $avatar;

    #[Column(type: 'boolean', options: ['default' => 0])]
    private bool $active;

    #[Column(type: 'datetime')]
    private DateTimeInterface $createdAt;

    #[Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $updatedAt;

    public function __construct(
        string $id,
        string $name,
        string $email,
        string $password,
        string|null $avatar,
        bool $active,
        DateTimeInterface $createdAt,
        ?DateTimeInterface $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->avatar = $avatar;
        $this->active = $active;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setAvatar(string|null $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getAvatar(): string|null
    {
        return $this->avatar;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }
}
