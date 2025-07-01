<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250401124855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE followers (id INT AUTO_INCREMENT NOT NULL, follower_id_id INT DEFAULT NULL, followed_id_id INT DEFAULT NULL, INDEX IDX_8408FDA7E8DDDA11 (follower_id_id), INDEX IDX_8408FDA7ECD373E5 (followed_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE followers ADD CONSTRAINT FK_8408FDA7E8DDDA11 FOREIGN KEY (follower_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE followers ADD CONSTRAINT FK_8408FDA7ECD373E5 FOREIGN KEY (followed_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE certifications DROP is_valid');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE followers DROP FOREIGN KEY FK_8408FDA7E8DDDA11');
        $this->addSql('ALTER TABLE followers DROP FOREIGN KEY FK_8408FDA7ECD373E5');
        $this->addSql('DROP TABLE followers');
        $this->addSql('ALTER TABLE certifications ADD is_valid TINYINT(1) DEFAULT NULL');
    }
}
