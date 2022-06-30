<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Minascafe\User\Domain\Entity\User;
use Minascafe\User\Domain\Repository\UserRepositoryInterface;
use Minascafe\User\Domain\ValueObject\UserEmail;
use Minascafe\User\Domain\ValueObject\UserId;
use Minascafe\User\Infrastructure\Persistence\Doctrine\Transform\UserTransform;

/**
 * @template T of object
 * @extends EntityRepository<T>
 */
final class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    private const USER_ACTIVE = 1;
    private const USER_INACTIVE = 0;

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

        if (self::USER_ACTIVE === $active) {
            $criteria['active'] = $active;
        } elseif (self::USER_INACTIVE === $active) {
            $criteria['active'] = $active;
        }

        if ($order) {
            $orderBy[$order] = 'ASC';
        }

        $findUser = $this->findBy($criteria, $orderBy, $limit, $offset);

        $users = [];

        foreach ($findUser as $user) {
            $users[] = UserTransform::entityToDomain($user);
        }

        return $users;
    }

    public function findById(UserId $userId): ?User
    {
        $findUser = $this->find($userId->value());

        if (!$findUser) {
            return null;
        }

        return UserTransform::entityToDomain($findUser);
    }

    public function findByEmail(UserEmail $userEmail): ?User
    {
        $findUser = $this->findOneBy([
            'email' => $userEmail->value(),
        ]);

        if (!$findUser) {
            return null;
        }

        return UserTransform::entityToDomain($findUser);
    }

    public function create(User $user): void
    {
        $entityUser = UserTransform::domainToEntity($user);

        $this->_em->persist($entityUser);
        $this->_em->flush();
    }

    public function update(User $user): void
    {
        $entityUser = UserTransform::domainToEntity($user);

        $findUser = $this->find($entityUser->getId());

        $findUser->setName($entityUser->getName());
        $findUser->setActive($entityUser->isActive());
        $findUser->setUpdatedAt($entityUser->getUpdatedAt());

        $this->_em->flush();
    }

    public function delete(User $user): void
    {
        $entityUser = UserTransform::domainToEntity($user);

        $findUser = $this->find($entityUser->getId());

        $this->_em->remove($findUser);
        $this->_em->flush();
    }
}
