<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

readonly class AppMailer
{
    public function __construct(
        private MailerInterface $mailer,
        private string $appDns,
        private string $userEmail,
    ) {
    }

    public function sendTestCardsNotification(int $cardsNumber): void
    {
        $subject = $cardsNumber
            ? 'Des cartes sont prêtes à être révisées !'
            : 'Pas de révision aujourd\'hui';
        $htmlBody = $cardsNumber
            ? sprintf(
                '<h2>C\'est l\'heure du test !</h2>
                    <p>Vous avez %d cartes à passer en revue aujourd\'hui !</p>
                    <p>Cliquez <a href="%s/cards/test" target="_blank">ici</a> pour tester vos connaissances</p>',
                $cardsNumber,
                $this->appDns,
            )
            : '<h2>Aujourd\'hui, c\'est repos !</h2>
                <p>Aucune carte a passer en revue ce jour.</p>';

        $email = (new Email())
            ->from('leitner@box.com')
            ->to($this->userEmail)
            ->subject($subject)
            ->html($htmlBody);

        $this->mailer->send($email);
    }
}
