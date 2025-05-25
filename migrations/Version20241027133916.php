<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241027133916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card (id UUID NOT NULL, question VARCHAR(255) NOT NULL, answer VARCHAR(255) NOT NULL, initial_test_date DATE DEFAULT NULL, active BOOLEAN NOT NULL, image VARCHAR(255) DEFAULT NULL, delay INT DEFAULT 0 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN card.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE card ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE card ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE card_id_seq CASCADE');
        $this->addSql('DROP TABLE card');
    }
}
