<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Minascafe\User\Domain\Entity\UserToken;
use Minascafe\User\Domain\Repository\UserTokenRepositoryInterface;
use Minascafe\User\Domain\ValueObject\UserTokenToken;
use Minascafe\User\Infrastructure\Persistence\Doctrine\Entity\User;
use Minascafe\User\Infrastructure\Persistence\Doctrine\Transform\UserTokenTransform;

/**
 * @template T of object
 * @extends EntityRepository<T>
 */
final class UserTokenRepository extends EntityRepository implements UserTokenRepositoryInterface
{
    public function findUserTokenByToken(UserTokenToken $userTokenToken): UserToken|null
    {
        $findUserToken = $this->findOneBy([
            'token' => $userTokenToken->value(),
        ]);

        if (!$findUserToken) {
            return null;
        }

        return UserTokenTransform::entityToDomain($findUserToken);
    }

    public function create(UserToken $userToken): UserToken
    {
        $entityUserToken = UserTokenTransform::domainToEntity($userToken);

        $findUser = $this->_em->find(User::class, $entityUserToken->getUser()->getId());
        $entityUserToken->setUser($findUser);

        $this->_em->persist($entityUserToken);
        $this->_em->flush();

        return $userToken;
    }
}
