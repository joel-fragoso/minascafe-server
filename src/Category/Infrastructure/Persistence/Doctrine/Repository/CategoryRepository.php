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
    public function all(): array
    {
        return $this->findAll();
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
        $entityManager = $this->getEntityManager();
        $entityManager->persist(CategoryTransform::domainToEntity($category));
        $entityManager->flush();
    }

    public function update(Category $category): void
    {
        $entityManager = $this->getEntityManager();
        // $entityManager->update(CategoryTransform::domainToEntity($category));
        $entityManager->flush();
    }
}
