<?php

declare(strict_types=1);

namespace Domain;

interface Notification
{
    public function sendTestCardsNotification(string $to, string $subject, string $htmlBody): void;
}
