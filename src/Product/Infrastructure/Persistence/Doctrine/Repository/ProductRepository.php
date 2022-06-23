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
    private const PRODUCT_ACTIVE = 1;
    private const PRODUCT_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public function findAll(
        ?int $active = null,
        ?string $order = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $criteria = [];
        $orderBy = null;

        if (self::PRODUCT_ACTIVE === $active) {
            $criteria['active'] = $active;
        } elseif (self::PRODUCT_INACTIVE === $active) {
            $criteria['active'] = $active;
        }

        if ($order) {
            $orderBy[$order] = 'ASC';
        }

        $findProducts = $this->findBy($criteria, $orderBy, $limit, $offset);

        $products = [];

        foreach ($findProducts as $product) {
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
        $findProduct->setActive($entityProduct->isActive());

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
