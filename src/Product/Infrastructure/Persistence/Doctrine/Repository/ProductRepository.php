<?php

declare(strict_types=1);

namespace Minascafe\Product\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Minascafe\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use Minascafe\Product\Domain\Entity\Product;
use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;
use Minascafe\Product\Domain\ValueObject\ProductId;
use Minascafe\Product\Domain\ValueObject\ProductName;
use Minascafe\Product\Infrastructure\Persistence\Doctrine\Transform\ProductTransform;

/**
 * @template T of object
 * @extends EntityRepository<T>
 */
final class ProductRepository extends EntityRepository implements ProductRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findAllProducts(): array
    {
        $products = [];

        foreach ($this->findAll() as $product) {
            $products[] = ProductTransform::entityToDomain($product);
        }

        return $products;
    }

    public function findById(ProductId $productId): ?Product
    {
        $findProduct = $this->find($productId->value());

        if (!$findProduct) {
            return null;
        }

        return ProductTransform::entityToDomain($findProduct);
    }

    public function findByName(ProductName $productName): ?Product
    {
        $findProduct = $this->findOneBy([
            'name' => $productName->value(),
        ]);

        if (!$findProduct) {
            return null;
        }

        return ProductTransform::entityToDomain($findProduct);
    }

    public function create(Product $product): void
    {
        $entityProduct = ProductTransform::domainToEntity($product);

        $findCategory = $this->_em->find(Category::class, $entityProduct->getCategory()->getId());
        $entityProduct->setCategory($findCategory);

        $this->_em->persist($entityProduct);
        $this->_em->flush();
    }

    public function update(Product $product): void
    {
        $entityProduct = ProductTransform::domainToEntity($product);

        $findCategory = $this->_em->find(Category::class, $entityProduct->getCategory()->getId());
        $findProduct = $this->find($entityProduct->getId());

        $findProduct->setCategory($findCategory);
        $findProduct->setName($entityProduct->getName());
        $findProduct->setPrice($entityProduct->getPrice());

        $this->_em->flush();
    }

    public function delete(Product $product): void
    {
        $entityProduct = ProductTransform::domainToEntity($product);

        $findProduct = $this->find($entityProduct->getId());

        $this->_em->remove($findProduct);
        $this->_em->flush();
    }
}
