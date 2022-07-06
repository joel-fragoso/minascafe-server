<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Adapter;

use Exception;
use Minascafe\User\Application\Adapter\MailAdapterInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Psr\Container\ContainerInterface;

final class PHPMailerAdapter implements MailAdapterInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function send(array $to, string $subject, string $template): void
    {
        $settings = $this->container->get('settings')['phpmailer'];

        $phpmailer = new PHPMailer(true);

        try {
            $phpmailer->SMTPDebug = SMTP::DEBUG_SERVER;
            $phpmailer->isSMTP();
            $phpmailer->Host = $settings['host'];
            $phpmailer->SMTPAuth = $settings['smtp_auth'];
            $phpmailer->Username = $settings['username'];
            $phpmailer->Password = $settings['password'];
            // $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $phpmailer->Port = $settings['port'];
            $phpmailer->CharSet = 'UTF-8';

            $phpmailer->setFrom('minascafe17@gmail.com', 'Minas Café');
            $phpmailer->addAddress($to['email'], $to['name']);
            // $phpmailer->addAddress('ellen@example.com');
            // $phpmailer->addReplyTo('info@example.com', 'Information');
            // $phpmailer->addCC('cc@example.com');
            // $phpmailer->addBCC('bcc@example.com');

            // $phpmailer->addAttachment('/var/tmp/file.tar.gz');
            // $phpmailer->addAttachment('/tmp/image.jpg', 'new.jpg');

            $phpmailer->isHTML(true);
            $phpmailer->Subject = $subject;
            $phpmailer->Body = $template;
            // $phpmailer->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $phpmailer->send();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
