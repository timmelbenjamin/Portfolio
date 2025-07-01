<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250420123904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__reservation AS SELECT id, objet_id, date FROM reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reservation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reservation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, objet_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, date DATETIME NOT NULL, CONSTRAINT FK_42C84955F520CF5A FOREIGN KEY (objet_id) REFERENCES objet (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO reservation (id, objet_id, date) SELECT id, objet_id, date FROM __temp__reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__reservation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C84955F520CF5A ON reservation (objet_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C84955A76ED395 ON reservation (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__reservation AS SELECT id, objet_id, date FROM reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reservation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reservation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, objet_id INTEGER DEFAULT NULL, date DATETIME NOT NULL, CONSTRAINT FK_42C84955F520CF5A FOREIGN KEY (objet_id) REFERENCES objet (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO reservation (id, objet_id, date) SELECT id, objet_id, date FROM __temp__reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__reservation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C84955F520CF5A ON reservation (objet_id)
        SQL);
    }
}
