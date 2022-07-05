<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use DateTimeImmutable;
use Minascafe\User\Application\Adapter\MailAdapterInterface;
use Minascafe\User\Application\Adapter\TemplateAdapterInterface;
use Minascafe\User\Domain\Entity\UserToken;
use Minascafe\User\Domain\Exception\UserNotFoundException;
use Minascafe\User\Domain\Repository\UserRepositoryInterface;
use Minascafe\User\Domain\Repository\UserTokenRepositoryInterface;
use Minascafe\User\Domain\ValueObject\UserEmail;
use Minascafe\User\Domain\ValueObject\UserTokenCreatedAt;
use Minascafe\User\Domain\ValueObject\UserTokenId;
use Minascafe\User\Domain\ValueObject\UserTokenToken;
use Minascafe\User\Domain\ValueObject\UserTokenUpdatedAt;

final class SendForgotPasswordUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserTokenRepositoryInterface $userTokenRepository,
        private MailAdapterInterface $mailAdapter,
        private TemplateAdapterInterface $templateAdapter,
    ) {
    }

    public function execute(SendForgotPasswordUseCaseRequest $sendForgotPasswordUseCaseRequest): void
    {
        $userEmail = $sendForgotPasswordUseCaseRequest->email();

        $findUser = $this->userRepository->findByEmail(new UserEmail($userEmail));

        if (!$findUser) {
            throw new UserNotFoundException("O usuário '{$userEmail}' não foi encontrado");
        }

        $userToken = UserToken::create(
            new UserTokenId(UserTokenId::generate()),
            $findUser,
            new UserTokenToken(UserTokenToken::generate()),
            new UserTokenCreatedAt(new DateTimeImmutable()),
            new UserTokenUpdatedAt(null),
        );

        $createdUserToken = $this->userTokenRepository->create($userToken);

        $template = $this->templateAdapter->render(
            'forgot-password.twig',
            [
                'name' => $createdUserToken->user()->name()->value(),
                'link' => "{$_ENV['APP_WEB_URL']}/senha/reseta?token={$createdUserToken->token()->value()}",
            ],
        );

        $this->mailAdapter->send(
            [
                'name' => $createdUserToken->user()->name()->value(),
                'email' => $createdUserToken->user()->email()->value(),
            ],
            '[Minas Café] Recuperação de senha',
            $template,
        );
    }
}
