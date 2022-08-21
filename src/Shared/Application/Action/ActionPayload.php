<?php

declare(strict_types=1);

namespace Minascafe\Shared\Application\Action;

use JsonSerializable;
use ReturnTypeWillChange;

class ActionPayload implements JsonSerializable
{
    public function __construct(
        private readonly int $statusCode = 200,
        private readonly mixed $data = null,
        private readonly ?ActionError $error = null
    ) {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function getError(): ?ActionError
    {
        return $this->error;
    }

    /**
     * @return array<string, mixed>
     */
    #[ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        $payload = [
            'statusCode' => $this->statusCode,
        ];

        if (null !== $this->data) {
            $payload['data'] = $this->data;
        } elseif (null !== $this->error) {
            $payload['error'] = $this->error;
        }

        return $payload;
    }
}
