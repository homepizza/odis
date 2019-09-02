<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190902122020 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE history_statuses ADD asignee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE history_statuses ADD CONSTRAINT FK_B0B725B63E0BCD7 FOREIGN KEY (asignee_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B0B725B63E0BCD7 ON history_statuses (asignee_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE history_statuses DROP FOREIGN KEY FK_B0B725B63E0BCD7');
        $this->addSql('DROP INDEX IDX_B0B725B63E0BCD7 ON history_statuses');
        $this->addSql('ALTER TABLE history_statuses DROP asignee_id');
    }
}
