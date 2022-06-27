<?php

declare(strict_types=1);

namespace Minascafe\Product\Infrastructure\Persistence\Doctrine\Transform;

use Minascafe\Category\Infrastructure\Persistence\Doctrine\Transform\CategoryTransform;
use Minascafe\Product\Domain\Entity\Product as DomainProduct;
use Minascafe\Product\Domain\ValueObject\ProductActive;
use Minascafe\Product\Domain\ValueObject\ProductCreatedAt;
use Minascafe\Product\Domain\ValueObject\ProductId;
use Minascafe\Product\Domain\ValueObject\ProductName;
use Minascafe\Product\Domain\ValueObject\ProductPrice;
use Minascafe\Product\Infrastructure\Persistence\Doctrine\Entity\Product as EntityProduct;

final class ProductTransform
{
    public static function entityToDomain(EntityProduct $entityProduct): DomainProduct
    {
        return DomainProduct::create(
            new ProductId($entityProduct->getId()),
            CategoryTransform::entityToDomain($entityProduct->getCategory()),
            new ProductName($entityProduct->getName()),
            new ProductPrice($entityProduct->getPrice()),
            new ProductActive($entityProduct->isActive()),
            new ProductCreatedAt($entityProduct->getCreatedAt())
        );
    }

    public static function domainToEntity(DomainProduct $domainProduct): EntityProduct
    {
        return new EntityProduct(
            $domainProduct->id()->value(),
            CategoryTransform::domainToEntity($domainProduct->category()),
            $domainProduct->name()->value(),
            $domainProduct->price()->value(),
            $domainProduct->isActive()->value(),
            $domainProduct->createdAt()->value()
        );
    }
}
