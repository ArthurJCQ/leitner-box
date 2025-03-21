<?php

declare(strict_types=1);

namespace Application;

use Domain\CardRepositoryInterface;
use Domain\Notification;

readonly class SendDailyCardsUseCase
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
        private Notification $mailer,
        private string $userEmail,
        private string $appDns,
    ) {
    }

    public function execute(): void
    {
        $cardsToTest = $this->cardRepository->findTodayCards();
        $cardsNumber = iterator_count($cardsToTest);

        $mailSubject = $cardsNumber
            ? 'Des cartes sont prêtes à être révisées !'
            : 'Pas de révision aujourd\'hui';
        $mailHtmlBody = $cardsNumber
            ? sprintf(
                '<h2>C\'est l\'heure du test !</h2>
                    <p>Vous avez %d cartes à passer en revue aujourd\'hui !</p>
                    <p>Cliquez <a href="%s/cards/test" target="_blank">ici</a> pour tester vos connaissances</p>',
                $cardsNumber,
                $this->appDns,
            )
            : '<h2>Aujourd\'hui, c\'est repos !</h2>
                <p>Aucune carte a passer en revue ce jour.</p>';

        $this->mailer->sendTestCardsNotification($this->userEmail, $mailSubject, $mailHtmlBody);
    }
}
