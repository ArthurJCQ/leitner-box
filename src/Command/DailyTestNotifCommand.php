<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\CardRepository;
use App\Service\AppMailer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:daily-test-notif',
    description: 'Daily Notification for test',
)]
class DailyTestNotifCommand extends Command
{
    public function __construct(
        private readonly CardRepository $cardRepository,
        private readonly AppMailer $appMailer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cardsToTest = $this->cardRepository->findTodayCards();

        $this->appMailer->sendTestCardsNotification(iterator_count($cardsToTest));

        return Command::SUCCESS;
    }
}
