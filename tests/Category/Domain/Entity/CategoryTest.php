<?php

declare(strict_types=1);

namespace Minascafe\Tests\Category\Domain\Entity;

use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Domain\ValueObject\CategoryActive;
use Minascafe\Category\Domain\ValueObject\CategoryIcon;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;
use Minascafe\Category\Domain\ValueObject\CategoryUpdatedAt;
use PHPUnit\Framework\TestCase;

final class CategoryTest extends TestCase
{
    public function testDeveSerCapazDeRetornarOsGetters(): void
    {
        $categoryId = CategoryId::generate();
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $category = Category::create(
            new CategoryId($categoryId),
            new CategoryName($categoryName),
            new CategoryIcon($categoryIcon),
            new CategoryActive(true),
            new CategoryUpdatedAt(null)
        );

        self::assertEquals($categoryId, $category->id()->value());
        self::assertEquals($categoryName, $category->name()->value());
        self::assertEquals($categoryIcon, $category->icon()->value());
        self::assertTrue($category->isActive()->value());
    }

    public function testDeveSerCapazDeInstanciarUmaCategoriaPeloArray(): void
    {
        $categoryId = CategoryId::generate();
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $payload = [
            'id' => $categoryId,
            'name' => $categoryName,
            'icon' => $categoryIcon,
            'active' => true,
            'updatedAt' => null,
        ];

        $category = Category::fromArray($payload);

        self::assertEquals($categoryId, $category->id()->value());
        self::assertEquals($categoryName, $category->name()->value());
        self::assertEquals($categoryIcon, $category->icon()->value());
        self::assertTrue($category->isActive()->value());
        self::assertNull($category->updatedAt()->value());
    }

    public function testDeveSerCapazDeRetornarUmArrayDeCategoria(): void
    {
        $categoryId = CategoryId::generate();
        $categoryName = 'Categoria';
        $categoryIcon = 'NomeDoIcone';

        $category = Category::create(
            new CategoryId($categoryId),
            new CategoryName($categoryName),
            new CategoryIcon($categoryIcon),
            new CategoryActive(true),
            new CategoryUpdatedAt(null)
        );

        $categoryData = $category->toArray();

        self::assertIsArray($categoryData);
        self::assertEquals($categoryId, $categoryData['id']);
        self::assertEquals($categoryName, $categoryData['name']);
        self::assertEquals($categoryIcon, $categoryData['icon']);
        self::assertTrue($categoryData['active']);
        self::assertNull($categoryData['updatedAt']);
    }
}
