<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine\Repository;

use Doctrine\DBAL\Connection;
use Domain\Card;
use Domain\CardRepositoryInterface;
use Ramsey\Uuid\Uuid;

class PostgresCardRepository implements CardRepositoryInterface
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function createNewCard(
        string $question,
        string $answer,
        \DateTimeInterface $initialTestDate,
        bool $active,
    ): void {
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
                'id' => Uuid::uuid4(),
                'question' => $question,
                'answer' => $answer,
                'initialTestDate' => $initialTestDate->format('Y-m-d'),
                'active' => $active,
                'delay' => 1,
            ])
            ->executeStatement();
    }

    public function editCard(
        string $id,
        string $question,
        string $answer,
        \DateTimeInterface $initialTestDate,
        bool $active,
    ): void {
        $this->connection->createQueryBuilder()
            ->update('card')
            ->set('question', ':question')
            ->set('answer', ':answer')
            ->set('initial_test_date', ':initialTestDate')
            ->set('active', ':active')
            ->set('updated_at', 'CURRENT_TIMESTAMP')
            ->where('id = :id')
            ->setParameters([
                'id' => $id,
                'question' => $question,
                'answer' => $answer,
                'initialTestDate' => $initialTestDate->format('Y-m-d'),
                'active' => $active,
            ])
            ->executeStatement();
    }

    public function removeCard(string $id): void
    {
        $this->connection->createQueryBuilder()
            ->delete('card')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeStatement();
    }

    public function solveCard(string $id, int $delay, \DateTimeInterface $initialTestDate, bool $active): void
    {
        $this->connection->createQueryBuilder()
            ->update('card')
            ->set('delay', ':delay')
            ->set('initial_test_date', ':initialTestDate')
            ->set('active', ':active')
            ->where('id = :id')
            ->setParameters([
                'id' => $id,
                'delay' => $delay,
                'initialTestDate' => $initialTestDate ? $initialTestDate->format('Y-m-d') : null,
                'active' => $active,
            ])
            ->executeStatement();
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
        return new Card(
            $row['question'],
            $row['answer'],
            $row['initial_test_date'] ? new \DateTime($row['initial_test_date']) : null,
            (bool) $row['active'],
            (int) $row['delay'],
            $row['id']
        );
    }
}
