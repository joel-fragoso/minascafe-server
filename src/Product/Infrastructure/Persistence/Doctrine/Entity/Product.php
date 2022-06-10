<?php

declare(strict_types=1);

namespace Minascafe\Product\Infrastructure\Persistence\Doctrine\Entity;

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

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $name;

    public function __construct(string $id, Category $category, string $name)
    {
        $this->id = $id;
        $this->category = $category;
        $this->name = $name;
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
}
