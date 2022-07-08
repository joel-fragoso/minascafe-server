<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Persistence\Doctrine\Transform;

use Minascafe\User\Domain\Entity\User as DomainUser;
use Minascafe\User\Domain\ValueObject\UserActive;
use Minascafe\User\Domain\ValueObject\UserAvatar;
use Minascafe\User\Domain\ValueObject\UserCreatedAt;
use Minascafe\User\Domain\ValueObject\UserEmail;
use Minascafe\User\Domain\ValueObject\UserId;
use Minascafe\User\Domain\ValueObject\UserName;
use Minascafe\User\Domain\ValueObject\UserPassword;
use Minascafe\User\Domain\ValueObject\UserUpdatedAt;
use Minascafe\User\Infrastructure\Persistence\Doctrine\Entity\User as EntityUser;

final class UserTransform
{
    public static function entityToDomain(EntityUser $entityUser): DomainUser
    {
        return DomainUser::create(
            new UserId($entityUser->getId()),
            new UserName($entityUser->getName()),
            new UserEmail($entityUser->getEmail()),
            new UserPassword($entityUser->getPassword()),
            new UserAvatar($entityUser->getAvatar()),
            new UserActive($entityUser->isActive()),
            new UserCreatedAt($entityUser->getCreatedAt()),
            new UserUpdatedAt($entityUser->getUpdatedAt()),
        );
    }

    public static function domainToEntity(DomainUser $domainUser): EntityUser
    {
        return new EntityUser(
            $domainUser->id()->value(),
            $domainUser->name()->value(),
            $domainUser->email()->value(),
            $domainUser->password()->value(),
            $domainUser->avatar()->value(),
            $domainUser->isActive()->value(),
            $domainUser->createdAt()->value(),
            $domainUser->updatedAt()->value(),
        );
    }
}
