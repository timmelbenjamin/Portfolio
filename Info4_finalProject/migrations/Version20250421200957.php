<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421200957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE objet ADD COLUMN img VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__objet AS SELECT id, categorie_id, sous_categorie_id, name, description, prix FROM objet
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE objet
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE objet (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categorie_id INTEGER DEFAULT NULL, sous_categorie_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, prix DOUBLE PRECISION NOT NULL, CONSTRAINT FK_46CD4C38BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_46CD4C38365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO objet (id, categorie_id, sous_categorie_id, name, description, prix) SELECT id, categorie_id, sous_categorie_id, name, description, prix FROM __temp__objet
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__objet
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_46CD4C38BCF5E72D ON objet (categorie_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_46CD4C38365BF48 ON objet (sous_categorie_id)
        SQL);
    }
}
