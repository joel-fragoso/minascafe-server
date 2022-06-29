<?php

declare(strict_types=1);

namespace Minascafe\Tests\Category\Domain\Entity;

use DateTimeImmutable;
use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Domain\ValueObject\CategoryActive;
use Minascafe\Category\Domain\ValueObject\CategoryCreatedAt;
use Minascafe\Category\Domain\ValueObject\CategoryIcon;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;
use PHPUnit\Framework\TestCase;

final class CategoryTest extends TestCase
{
    public function testDeveSerCapazDeRetornarOsGetters(): void
    {
        $categoryId = CategoryId::generate();
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';
        $categoryCreatedAt = new DateTimeImmutable();

        $category = Category::create(
            new CategoryId($categoryId),
            new CategoryName($categoryName),
            new CategoryIcon($categoryIcon),
            new CategoryActive(true),
            new CategoryCreatedAt($categoryCreatedAt)
        );

        self::assertEquals($categoryId, $category->id()->value());
        self::assertEquals($categoryName, $category->name()->value());
        self::assertEquals($categoryIcon, $category->icon()->value());
        self::assertTrue($category->isActive()->value());
        self::assertEquals($categoryCreatedAt, $category->createdAt()->value());
    }

    public function testDeveSerCapazDeInstanciarUmaCategoriaPeloArray(): void
    {
        $categoryId = CategoryId::generate();
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';
        $categoryCreatedAt = new DateTimeImmutable();

        $payload = [
            'id' => $categoryId,
            'name' => $categoryName,
            'icon' => $categoryIcon,
            'active' => true,
            'createdAt' => $categoryCreatedAt,
        ];

        $category = Category::fromArray($payload);

        self::assertEquals($categoryId, $category->id()->value());
        self::assertEquals($categoryName, $category->name()->value());
        self::assertEquals($categoryIcon, $category->icon()->value());
        self::assertTrue($category->isActive()->value());
        self::assertEquals($categoryCreatedAt, $category->createdAt()->value());
    }

    public function testDeveSerCapazDeRetornarUmArrayDeCategoria(): void
    {
        $categoryId = CategoryId::generate();
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';
        $categoryCreatedAt = new DateTimeImmutable();

        $category = Category::create(
            new CategoryId($categoryId),
            new CategoryName($categoryName),
            new CategoryIcon($categoryIcon),
            new CategoryActive(true),
            new CategoryCreatedAt($categoryCreatedAt)
        );

        $categoryData = $category->toArray();

        self::assertIsArray($categoryData);
        self::assertEquals($categoryId, $categoryData['id']);
        self::assertEquals($categoryName, $categoryData['name']);
        self::assertEquals($categoryIcon, $categoryData['icon']);
        self::assertTrue($categoryData['active']);
        self::assertEquals($categoryCreatedAt, $categoryData['createdAt']);
    }
}
