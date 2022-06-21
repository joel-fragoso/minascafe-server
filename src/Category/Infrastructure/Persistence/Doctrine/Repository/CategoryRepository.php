<?php

declare(strict_types=1);

namespace Minascafe\Category\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Minascafe\Category\Domain\Entity\Category;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;
use Minascafe\Category\Infrastructure\Persistence\Doctrine\Transform\CategoryTransform;

/**
 * @template T of object
 * @extends EntityRepository<T>
 */
class CategoryRepository extends EntityRepository implements CategoryRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findAllCategories(): array
    {
        $categories = [];

        foreach ($this->findBy([], ['name' => 'ASC']) as $category) {
            $categories[] = CategoryTransform::entityToDomain($category);
        }

        return $categories;
    }

    public function findById(CategoryId $categoryId): ?Category
    {
        $findCategory = $this->find($categoryId->value());

        if (!$findCategory) {
            return null;
        }

        return CategoryTransform::entityToDomain($findCategory);
    }

    public function findByName(CategoryName $categoryName): ?Category
    {
        $findCategory = $this->findOneBy([
            'name' => $categoryName->value(),
        ]);

        if (!$findCategory) {
            return null;
        }

        return CategoryTransform::entityToDomain($findCategory);
    }

    public function create(Category $category): void
    {
        $entityCategory = CategoryTransform::domainToEntity($category);

        $this->_em->persist($entityCategory);
        $this->_em->flush();
    }

    public function update(Category $category): void
    {
        $entityCategory = CategoryTransform::domainToEntity($category);

        $findCategory = $this->find($entityCategory->getId());

        $findCategory->setName($entityCategory->getName());
        $findCategory->setIcon($entityCategory->getIcon());

        $this->_em->flush();
    }

    public function delete(Category $category): void
    {
        $entityCategory = CategoryTransform::domainToEntity($category);

        $findCategory = $this->find($entityCategory->getId());

        $this->_em->remove($findCategory);
        $this->_em->flush();
    }
}
