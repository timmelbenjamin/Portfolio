<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250420122915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__reservation AS SELECT id, date FROM reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reservation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reservation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATETIME NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO reservation (id, date) SELECT id, date FROM __temp__reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__reservation
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD COLUMN user VARCHAR(255) NOT NULL
        SQL);
    }
}
