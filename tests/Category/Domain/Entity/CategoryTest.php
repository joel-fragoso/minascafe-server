<?php

declare(strict_types=1);

namespace Minascafe\Tests\Category\Domain\Entity;

use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;
use PHPUnit\Framework\TestCase;

final class CategoryTest extends TestCase
{
    public function testDeveSerCapazDeRetornarOsGetters(): void
    {
        $categoryId = CategoryId::generate();
        $categoryName = 'Categoria';

        $category = Category::create(new CategoryId($categoryId), new CategoryName($categoryName));

        self::assertEquals($categoryId, $category->id()->value());
        self::assertEquals($categoryName, $category->name()->value());
    }

    public function testDeveSerCapazDeInstanciarUmaCategoriaPeloArray(): void
    {
        $categoryId = CategoryId::generate();
        $categoryName = 'Categoria';

        $payload = [
            'id' => $categoryId,
            'name' => $categoryName,
        ];

        $category = Category::fromArray($payload);

        self::assertEquals($categoryId, $category->id()->value());
        self::assertEquals($categoryName, $category->name()->value());
    }

    public function testDeveSerCapazDeRetornarUmArrayDeCategoria(): void
    {
        $categoryId = CategoryId::generate();
        $categoryName = 'Categoria';

        $category = Category::create(new CategoryId($categoryId), new CategoryName($categoryName));

        $categoryData = $category->toArray();

        self::assertIsArray($categoryData);
        self::assertEquals($categoryId, $categoryData['id']);
        self::assertEquals($categoryName, $categoryData['name']);
    }
}
