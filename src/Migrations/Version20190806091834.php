<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190806091834 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE workflow (id INT AUTO_INCREMENT NOT NULL, status_id INT DEFAULT NULL, access_id INT DEFAULT NULL, INDEX IDX_65C598166BF700BD (status_id), INDEX IDX_65C598164FEA67CF (access_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, fullname VARCHAR(255) DEFAULT NULL, auth_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statuses (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, comment VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, comment VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tasks (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, asignee_id INT DEFAULT NULL, priority_id INT NOT NULL, status_id INT NOT NULL, type_id INT NOT NULL, area_id INT NOT NULL, created_at DATETIME NOT NULL, due_date DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, INDEX IDX_50586597F675F31B (author_id), INDEX IDX_5058659763E0BCD7 (asignee_id), INDEX IDX_50586597497B19F9 (priority_id), INDEX IDX_505865976BF700BD (status_id), INDEX IDX_50586597C54C8C93 (type_id), INDEX IDX_50586597BD0F409C (area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE priorities (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, value INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domain_areas (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, comment VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE workflow ADD CONSTRAINT FK_65C598166BF700BD FOREIGN KEY (status_id) REFERENCES statuses (id)');
        $this->addSql('ALTER TABLE workflow ADD CONSTRAINT FK_65C598164FEA67CF FOREIGN KEY (access_id) REFERENCES statuses (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_5058659763E0BCD7 FOREIGN KEY (asignee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597497B19F9 FOREIGN KEY (priority_id) REFERENCES priorities (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_505865976BF700BD FOREIGN KEY (status_id) REFERENCES statuses (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597C54C8C93 FOREIGN KEY (type_id) REFERENCES types (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597BD0F409C FOREIGN KEY (area_id) REFERENCES domain_areas (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597F675F31B');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_5058659763E0BCD7');
        $this->addSql('ALTER TABLE workflow DROP FOREIGN KEY FK_65C598166BF700BD');
        $this->addSql('ALTER TABLE workflow DROP FOREIGN KEY FK_65C598164FEA67CF');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_505865976BF700BD');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597C54C8C93');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597497B19F9');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597BD0F409C');
        $this->addSql('DROP TABLE workflow');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE statuses');
        $this->addSql('DROP TABLE types');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE priorities');
        $this->addSql('DROP TABLE domain_areas');
    }
}
