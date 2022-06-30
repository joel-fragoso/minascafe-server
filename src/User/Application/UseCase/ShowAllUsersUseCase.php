<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use Minascafe\User\Domain\Repository\UserRepositoryInterface;

final class ShowAllUsersUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function execute(
        ShowAllUsersUseCaseRequest $showAllUsersUseCaseRequest
    ): ShowAllUsersUseCaseResponse {
        $users = $this->userRepository->findAll(
            $showAllUsersUseCaseRequest->active(),
            $showAllUsersUseCaseRequest->order(),
            $showAllUsersUseCaseRequest->limit(),
            $showAllUsersUseCaseRequest->offset()
        );

        return new ShowAllUsersUseCaseResponse($users);
    }
}
