<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use Psr\Http\Message\UploadedFileInterface;

final class UpdateUserAvatarUseCaseRequest
{
    public function __construct(
        private string $userId,
        private UploadedFileInterface $avatar,
    ) {
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function avatar(): UploadedFileInterface
    {
        return $this->avatar;
    }
}
