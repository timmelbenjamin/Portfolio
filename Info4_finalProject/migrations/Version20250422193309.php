<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250422193309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__objet AS SELECT id, categorie_id, sous_categorie_id, name, description, prix, img FROM objet
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE objet
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE objet (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categorie_id INTEGER DEFAULT NULL, sous_categorie_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, prix DOUBLE PRECISION NOT NULL, img VARCHAR(255) NOT NULL, CONSTRAINT FK_46CD4C38BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_46CD4C38365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_46CD4C38A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO objet (id, categorie_id, sous_categorie_id, name, description, prix, img) SELECT id, categorie_id, sous_categorie_id, name, description, prix, img FROM __temp__objet
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__objet
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_46CD4C38365BF48 ON objet (sous_categorie_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_46CD4C38BCF5E72D ON objet (categorie_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_46CD4C38A76ED395 ON objet (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, password, roles FROM user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO user (id, email, password, roles) SELECT id, email, password, roles FROM __temp__user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__user
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__objet AS SELECT id, categorie_id, sous_categorie_id, name, description, prix, img FROM objet
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE objet
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE objet (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categorie_id INTEGER DEFAULT NULL, sous_categorie_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, prix DOUBLE PRECISION NOT NULL, img VARCHAR(255) NOT NULL, CONSTRAINT FK_46CD4C38BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_46CD4C38365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO objet (id, categorie_id, sous_categorie_id, name, description, prix, img) SELECT id, categorie_id, sous_categorie_id, name, description, prix, img FROM __temp__objet
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
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password FROM user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, roles VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO user (id, email, roles, password) SELECT id, email, roles, password FROM __temp__user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__user
        SQL);
    }
}
