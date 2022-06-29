<?php

declare(strict_types=1);

namespace Minascafe\Product\Infrastructure\Persistence\Doctrine\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Minascafe\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use Minascafe\Product\Infrastructure\Persistence\Doctrine\Repository\ProductRepository;

#[Entity(repositoryClass: ProductRepository::class), Table(name: 'products')]
final class Product
{
    #[Id, Column(type: 'guid', length: 36)]
    private string $id;

    #[ManyToOne(targetEntity: Category::class, fetch: 'EAGER'), JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: false)]
    private Category $category;

    #[Column(type: 'string', unique: true, length: 45)]
    private string $name;

    #[Column(type: 'decimal', precision: 10, scale: 2)]
    private float $price;

    #[Column(type: 'boolean', options: ['default' => 0])]
    private bool $active;

    #[Column(type: 'datetime', nullable: true)]
    private DateTimeInterface $createdAt;

    public function __construct(
        string $id,
        Category $category,
        string $name,
        float $price,
        bool $active,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->category = $category;
        $this->name = $name;
        $this->price = $price;
        $this->active = $active;
        $this->createdAt = $createdAt;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
