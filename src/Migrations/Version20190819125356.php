<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190819125356 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE filters ADD status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE filters ADD CONSTRAINT FK_7877678D6BF700BD FOREIGN KEY (status_id) REFERENCES statuses (id)');
        $this->addSql('CREATE INDEX IDX_7877678D6BF700BD ON filters (status_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE filters DROP FOREIGN KEY FK_7877678D6BF700BD');
        $this->addSql('DROP INDEX IDX_7877678D6BF700BD ON filters');
        $this->addSql('ALTER TABLE filters DROP status_id');
    }
}
