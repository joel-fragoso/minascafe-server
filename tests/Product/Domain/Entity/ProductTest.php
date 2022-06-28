<?php

declare(strict_types=1);

namespace Minascafe\Tests\Product\Domain\Entity;

use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Domain\ValueObject\CategoryActive;
use Minascafe\Category\Domain\ValueObject\CategoryIcon;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;
use Minascafe\Category\Domain\ValueObject\CategoryUpdatedAt;
use Minascafe\Product\Domain\Entity\Product;
use Minascafe\Product\Domain\ValueObject\ProductActive;
use Minascafe\Product\Domain\ValueObject\ProductId;
use Minascafe\Product\Domain\ValueObject\ProductName;
use Minascafe\Product\Domain\ValueObject\ProductPrice;
use Minascafe\Product\Domain\ValueObject\ProductUpdatedAt;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    public function testDeveSerCapazDeRetornarOsGetters(): void
    {
        $productId = ProductId::generate();
        $productName = 'Produto';
        $productPrice = 1.00;

        $category = Category::create(
            new CategoryId(CategoryId::generate()),
            new CategoryName('Categoria'),
            new CategoryIcon('NomeDoIcone'),
            new CategoryActive(true),
            new CategoryUpdatedAt(null)
        );

        $product = Product::create(
            new ProductId($productId),
            $category,
            new ProductName($productName),
            new ProductPrice($productPrice),
            new ProductActive(true),
            new ProductUpdatedAt(null)
        );

        self::assertEquals($productId, $product->id()->value());
        self::assertEquals($category, $product->category());
        self::assertEquals($productName, $product->name()->value());
        self::assertEquals($productPrice, $product->price()->value());
        self::assertTrue($product->isActive()->value());
        self::assertNull($product->updatedAt()->value());
    }

    public function testDeveSerCapazDeInstanciarUmProdutoPeloArray(): void
    {
        $productId = ProductId::generate();
        $productName = 'Produto';
        $productPrice = 1.00;

        $payload = [
            'id' => $productId,
            'name' => $productName,
            'price' => $productPrice,
            'active' => true,
            'updatedAt' => null,
            'category' => [
                'id' => CategoryId::generate(),
                'name' => 'Categoria',
                'icon' => 'NomeDoIcone',
                'active' => true,
                'updatedAt' => null,
            ],
        ];

        $product = Product::fromArray($payload);

        self::assertEquals($productId, $product->id()->value());
        self::assertEquals('Categoria', $product->category()->name()->value());
        self::assertEquals($productName, $product->name()->value());
        self::assertEquals($productPrice, $product->price()->value());
        self::assertTrue($product->isActive()->value());
        self::assertNull($product->updatedAt()->value());
    }

    public function testDeveSerCapazDeRetornarUmArrayDeProduto(): void
    {
        $productId = ProductId::generate();
        $productName = 'Produto';
        $productPrice = 1.00;

        $category = Category::create(
            new CategoryId(CategoryId::generate()),
            new CategoryName('Categoria'),
            new CategoryIcon('NomeDoIcone'),
            new CategoryActive(true),
            new CategoryUpdatedAt(null)
        );

        $product = Product::create(
            new ProductId($productId),
            $category,
            new ProductName($productName),
            new ProductPrice($productPrice),
            new ProductActive(true),
            new ProductUpdatedAt(null)
        );

        $productData = $product->toArray();

        self::assertIsArray($productData);
        self::assertEquals($productId, $productData['id']);
        self::assertEquals($productName, $productData['name']);
        self::assertEquals($productPrice, $productData['price']);
        self::assertTrue($productData['active']);
        self::assertNull($productData['updatedAt']);
    }
}
