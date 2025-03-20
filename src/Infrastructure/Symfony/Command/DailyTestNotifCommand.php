<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\Command;

use Application\UseCase\SendDailyCardsUseCase;
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
    public function __construct(private readonly SendDailyCardsUseCase $sendDailyCardsUseCase) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->sendDailyCardsUseCase->execute();

        return Command::SUCCESS;
    }
}
