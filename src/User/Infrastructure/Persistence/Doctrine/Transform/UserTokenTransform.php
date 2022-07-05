<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Persistence\Doctrine\Transform;

use Minascafe\User\Domain\Entity\UserToken as DomainUserToken;
use Minascafe\User\Domain\ValueObject\UserTokenCreatedAt;
use Minascafe\User\Domain\ValueObject\UserTokenId;
use Minascafe\User\Domain\ValueObject\UserTokenToken;
use Minascafe\User\Domain\ValueObject\UserTokenUpdatedAt;
use Minascafe\User\Infrastructure\Persistence\Doctrine\Entity\UserToken as EntityUserToken;

final class UserTokenTransform
{
    public static function entityToDomain(EntityUserToken $entityUserToken): DomainUserToken
    {
        return DomainUserToken::create(
            new UserTokenId($entityUserToken->getId()),
            UserTransform::entityToDomain($entityUserToken->getUser()),
            new UserTokenToken($entityUserToken->getToken()),
            new UserTokenCreatedAt($entityUserToken->getCreatedAt()),
            new UserTokenUpdatedAt($entityUserToken->getUpdatedAt()),
        );
    }

    public static function domainToEntity(DomainUserToken $domainUserToken): EntityUserToken
    {
        return new EntityUserToken(
            $domainUserToken->id()->value(),
            UserTransform::domainToEntity($domainUserToken->user()),
            $domainUserToken->token()->value(),
            $domainUserToken->createdAt()->value(),
            $domainUserToken->updatedAt()->value(),
        );
    }
}
