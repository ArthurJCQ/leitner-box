<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine\Repository;

use Doctrine\DBAL\Connection;
use Domain\Card;
use Domain\CardRepositoryInterface;
use Domain\Exception\CannotCreateCard;
use Domain\Exception\CannotEditCard;
use Domain\Exception\CannotRemoveCard;
use Ramsey\Uuid\Uuid;

class PostgresCardRepository implements CardRepositoryInterface
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function createNewCard(Card $card): void
    {
        try {
            $this->connection->createQueryBuilder()
                ->insert('card')
                ->values([
                    'id' => ':id',
                    'question' => ':question',
                    'answer' => ':answer',
                    'initial_test_date' => ':initialTestDate',
                    'active' => ':active',
                    'delay' => ':delay',
                    'created_at' => 'CURRENT_TIMESTAMP',
                ])
                ->setParameters([
                    'id' => $card->id ?? Uuid::uuid4(),
                    'question' => $card->question,
                    'answer' => $card->answer,
                    'initialTestDate' => $card->initialTestDate?->format('Y-m-d'),
                    'active' => $card->active,
                    'delay' => $card->delay,
                ])
                ->executeStatement();
        } catch (\Throwable $e) {
            throw new CannotCreateCard('Failed to create card: ' . $e->getMessage(), 0, $e);
        }
    }

    public function editCard(Card $card): void
    {
        try {
            $this->connection->createQueryBuilder()
                ->update('card')
                ->set('question', ':question')
                ->set('answer', ':answer')
                ->set('initial_test_date', ':initialTestDate')
                ->set('active', ':active')
                ->set('delay', ':delay')
                ->set('updated_at', 'CURRENT_TIMESTAMP')
                ->where('id = :id')
                ->setParameters([
                    'id' => $card->id,
                    'question' => $card->question,
                    'answer' => $card->answer,
                    'initialTestDate' => $card->initialTestDate ? $card->initialTestDate->format('Y-m-d') : null,
                    'active' => $card->active,
                    'delay' => $card->delay,
                ])
                ->executeStatement();
        } catch (\Throwable $e) {
            throw new CannotEditCard('Failed to edit card: ' . $e->getMessage(), 0, $e);
        }
    }

    public function removeCard(string $id): void
    {
        try {
            $this->connection->createQueryBuilder()
                ->delete('card')
                ->where('id = :id')
                ->setParameter('id', $id)
                ->executeStatement();
        } catch (\Throwable $e) {
            throw new CannotRemoveCard('Failed to remove card: ' . $e->getMessage(), 0, $e);
        }
    }

    /** @return iterable<Card> */
    public function findTodayCards(): iterable
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $result = $queryBuilder
            ->select('c.id, c.question, c.answer, c.delay, c.initial_test_date, c.active')
            ->from('card', 'c')
            ->where('c.initial_test_date + (c.delay * INTERVAL \'1 day\') <= CURRENT_DATE')
            ->andWhere('c.active = :active')
            ->setParameter('active', true)
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($result as $row) {
            yield $this->mapToCard($row);
        }
    }

    public function findCard(string $id): ?Card
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $result = $queryBuilder
            ->select('c.id, c.question, c.answer, c.delay, c.initial_test_date, c.active')
            ->from('card', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();

        if ($result === false) {
            return null;
        }

        return $this->mapToCard($result);
    }

    /** @return iterable<Card> */
    public function listAllCards(): iterable
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $result = $queryBuilder
            ->select('c.id, c.question, c.answer, c.delay, c.initial_test_date, c.active')
            ->from('card', 'c')
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($result as $row) {
            yield $this->mapToCard($row);
        }
    }

    /**
     * Maps a database row to a Card domain object
     */
    private function mapToCard(array $row): Card
    {
        return Card::create(
            $row['question'],
            $row['answer'],
            $row['initial_test_date'] ? new \DateTime($row['initial_test_date']) : new \DateTime(),
            (bool) $row['active'],
            (int) $row['delay'],
            $row['id'],
        );
    }
}
