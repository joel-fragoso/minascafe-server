<?php

declare(strict_types=1);

namespace Minascafe\Category\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Minascafe\Category\Infrastructure\Persistence\Doctrine\Repository\CategoryRepository;

#[Entity(repositoryClass: CategoryRepository::class), Table(name: 'categories')]
final class Category
{
    #[Id, Column(type: 'guid', length: 36)]
    private string $id;

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $name;

    #[Column(type: 'string', nullable: false)]
    private string $icon;

    public function __construct(string $id, string $name, string $icon)
    {
        $this->id = $id;
        $this->name = $name;
        $this->icon = $icon;
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

    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }
}
