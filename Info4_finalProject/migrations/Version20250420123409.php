<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250420123409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__sous_categorie AS SELECT id, name FROM sous_categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sous_categorie
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sous_categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categorie_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, CONSTRAINT FK_52743D7BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO sous_categorie (id, name) SELECT id, name FROM __temp__sous_categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__sous_categorie
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_52743D7BBCF5E72D ON sous_categorie (categorie_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__sous_categorie AS SELECT id, name FROM sous_categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sous_categorie
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sous_categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO sous_categorie (id, name) SELECT id, name FROM __temp__sous_categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__sous_categorie
        SQL);
    }
}
