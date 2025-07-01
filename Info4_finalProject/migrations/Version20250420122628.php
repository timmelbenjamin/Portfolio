<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250420122628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE objet (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, prix DOUBLE PRECISION NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reservation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATETIME NOT NULL, user VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sous_categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE objet
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sous_categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
