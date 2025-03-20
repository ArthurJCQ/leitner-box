<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\Mailer;

use Domain\Notification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

readonly class AppMailer implements Notification
{
    public function __construct(private MailerInterface $mailer) {}

    public function sendTestCardsNotification(string $to, string $subject, string $htmlBody): void
    {
        $email = (new Email())
            ->from('leitner@box.com')
            ->to($to)
            ->subject($subject)
            ->html($htmlBody);

        $this->mailer->send($email);
    }
}
