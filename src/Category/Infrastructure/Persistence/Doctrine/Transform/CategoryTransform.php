<?php

declare(strict_types=1);

namespace Minascafe\Category\Infrastructure\Persistence\Doctrine\Transform;

use Minascafe\Category\Domain\Entity\Category as DomainCategory;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;
use Minascafe\Category\Infrastructure\Persistence\Doctrine\Entity\Category as EntityCategory;

final class CategoryTransform
{
    public static function entityToDomain(EntityCategory $entityCategory): DomainCategory
    {
        return DomainCategory::create(
            new CategoryId($entityCategory->getId()),
            new CategoryName($entityCategory->getName())
        );
    }

    public static function domainToEntity(DomainCategory $domainCategory): EntityCategory
    {
        return new EntityCategory(
            $domainCategory->id()->value(),
            $domainCategory->name()->value()
        );
    }
}
