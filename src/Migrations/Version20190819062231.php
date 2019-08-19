<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190819062231 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE filters (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, asignee_id INT DEFAULT NULL, priority_id INT DEFAULT NULL, type_id INT DEFAULT NULL, area_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, due_from DATETIME DEFAULT NULL, due_to DATETIME DEFAULT NULL, INDEX IDX_7877678DF675F31B (author_id), INDEX IDX_7877678D63E0BCD7 (asignee_id), INDEX IDX_7877678D497B19F9 (priority_id), INDEX IDX_7877678DC54C8C93 (type_id), INDEX IDX_7877678DBD0F409C (area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE filters ADD CONSTRAINT FK_7877678DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE filters ADD CONSTRAINT FK_7877678D63E0BCD7 FOREIGN KEY (asignee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE filters ADD CONSTRAINT FK_7877678D497B19F9 FOREIGN KEY (priority_id) REFERENCES priorities (id)');
        $this->addSql('ALTER TABLE filters ADD CONSTRAINT FK_7877678DC54C8C93 FOREIGN KEY (type_id) REFERENCES types (id)');
        $this->addSql('ALTER TABLE filters ADD CONSTRAINT FK_7877678DBD0F409C FOREIGN KEY (area_id) REFERENCES domain_areas (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE filters');
    }
}
