<?php

declare(strict_types=1);

namespace Minascafe\Shared\Infrastructure\Logger\Monolog\Handler;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

final class DiscordHandler extends AbstractProcessingHandler
{
    public function __construct(
        private ClientInterface $client,
        private string $webHookUrl,
        private string $name = '',
    ) {
    }

    /**
     * @throws GuzzleException
     */
    protected function write(LogRecord $record): void
    {
        $content = strtr(
            '{datetime} {name}.{levelName}: {message}',
            [
                '{datetime}' => "**[{$record['datetime']->format('Y-m-d H:i:s')}]**",
                '{name}' => $this->name,
                '{levelName}' => "__{$record['level_name']}__",
                '{message}' => $record['message'],
            ]
        );

        $payload = ['content' => $content];

        $this->send($this->webHookUrl, $payload);
    }

    /**
     * @param array<string, string> $json
     *
     * @throws GuzzleException
     */
    private function send(string $webHookUrl, array $json): void
    {
        $this->client->request('POST', $webHookUrl, ['json' => $json]);
    }
}
