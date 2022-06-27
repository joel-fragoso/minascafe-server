<?php

declare(strict_types=1);

namespace Minascafe\Tests\Product\Domain\Entity;

use DateTimeImmutable;
use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Domain\ValueObject\CategoryActive;
use Minascafe\Category\Domain\ValueObject\CategoryCreatedAt;
use Minascafe\Category\Domain\ValueObject\CategoryIcon;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;
use Minascafe\Product\Domain\Entity\Product;
use Minascafe\Product\Domain\ValueObject\ProductActive;
use Minascafe\Product\Domain\ValueObject\ProductCreatedAt;
use Minascafe\Product\Domain\ValueObject\ProductId;
use Minascafe\Product\Domain\ValueObject\ProductName;
use Minascafe\Product\Domain\ValueObject\ProductPrice;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    public function testDeveSerCapazDeRetornarOsGetters(): void
    {
        $productId = ProductId::generate();
        $productName = 'Produto';
        $productPrice = 1.00;
        $productCreatedAt = new DateTimeImmutable();

        $category = Category::create(
            new CategoryId(CategoryId::generate()),
            new CategoryName('Categoria'),
            new CategoryIcon('NomeDoIcone'),
            new CategoryActive(true),
            new CategoryCreatedAt(new DateTimeImmutable())
        );

        $product = Product::create(
            new ProductId($productId),
            $category,
            new ProductName($productName),
            new ProductPrice($productPrice),
            new ProductActive(true),
            new ProductCreatedAt($productCreatedAt)
        );

        self::assertEquals($productId, $product->id()->value());
        self::assertEquals($category, $product->category());
        self::assertEquals($productName, $product->name()->value());
        self::assertEquals($productPrice, $product->price()->value());
        self::assertTrue($product->isActive()->value());
        self::assertEquals($productCreatedAt, $product->createdAt()->value());
    }

    public function testDeveSerCapazDeInstanciarUmProdutoPeloArray(): void
    {
        $productId = ProductId::generate();
        $productName = 'Produto';
        $productPrice = 1.00;
        $productCreatedAt = new DateTimeImmutable();

        $payload = [
            'id' => $productId,
            'name' => $productName,
            'price' => $productPrice,
            'active' => true,
            'createdAt' => $productCreatedAt,
            'category' => [
                'id' => CategoryId::generate(),
                'name' => 'Categoria',
                'icon' => 'NomeDoIcone',
                'active' => true,
                'createdAt' => new DateTimeImmutable(),
            ],
        ];

        $product = Product::fromArray($payload);

        self::assertEquals($productId, $product->id()->value());
        self::assertEquals('Categoria', $product->category()->name()->value());
        self::assertEquals($productName, $product->name()->value());
        self::assertEquals($productPrice, $product->price()->value());
        self::assertTrue($product->isActive()->value());
        self::assertEquals($productCreatedAt, $product->createdAt()->value());
    }

    public function testDeveSerCapazDeRetornarUmArrayDeProduto(): void
    {
        $productId = ProductId::generate();
        $productName = 'Produto';
        $productPrice = 1.00;
        $productCreatedAt = new DateTimeImmutable();

        $category = Category::create(
            new CategoryId(CategoryId::generate()),
            new CategoryName('Categoria'),
            new CategoryIcon('NomeDoIcone'),
            new CategoryActive(true),
            new CategoryCreatedAt(new DateTimeImmutable())
        );

        $product = Product::create(
            new ProductId($productId),
            $category,
            new ProductName($productName),
            new ProductPrice($productPrice),
            new ProductActive(true),
            new ProductCreatedAt($productCreatedAt)
        );

        $productData = $product->toArray();

        self::assertIsArray($productData);
        self::assertEquals($productId, $productData['id']);
        self::assertEquals($productName, $productData['name']);
        self::assertEquals($productPrice, $productData['price']);
        self::assertTrue($productData['active']);
        self::assertEquals($productCreatedAt, $productData['createdAt']);
    }
}
