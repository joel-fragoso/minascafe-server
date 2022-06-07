<?php

declare(strict_types=1);

namespace Minascafe\Category\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Minascafe\Category\Infrastructure\Persistence\Doctrine\Repository\CategoryRepository;

#[Entity(repositoryClass: CategoryRepository::class), Table(name: 'categories')]
final class Category implements JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private ?int $id;

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $name;

    public function __construct(?int $id = null, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, int|string>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}
