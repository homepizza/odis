<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190814114107 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE history_statuses (id INT AUTO_INCREMENT NOT NULL, task_id INT NOT NULL, status_id INT NOT NULL, date_status DATETIME NOT NULL, INDEX IDX_B0B725B8DB60186 (task_id), INDEX IDX_B0B725B6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE history_statuses ADD CONSTRAINT FK_B0B725B8DB60186 FOREIGN KEY (task_id) REFERENCES tasks (id)');
        $this->addSql('ALTER TABLE history_statuses ADD CONSTRAINT FK_B0B725B6BF700BD FOREIGN KEY (status_id) REFERENCES statuses (id)');
        $this->addSql('ALTER TABLE tasks ADD solution_link VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE history_statuses');
        $this->addSql('ALTER TABLE tasks DROP solution_link');
    }
}
