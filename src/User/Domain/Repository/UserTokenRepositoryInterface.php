<?php

declare(strict_types=1);

namespace Minascafe\User\Domain\Repository;

use Minascafe\User\Domain\Entity\UserToken;
use Minascafe\User\Domain\ValueObject\UserTokenToken;

interface UserTokenRepositoryInterface
{
    public function findUserTokenByToken(UserTokenToken $userTokenToken): UserToken|null;

    public function create(UserToken $userToken): UserToken;
}
