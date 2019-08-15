<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190815090120 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE statuses ADD class VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE priorities ADD class VARCHAR(50) DEFAULT NULL, ADD table_class VARCHAR(100) DEFAULT NULL');

        $this->addSql('UPDATE priorities SET class="c-badge--info", table_class="c-table__row--info" WHERE name="Низкий"');
        $this->addSql('UPDATE priorities SET class="c-badge--warning", table_class="c-table__row--warning" WHERE name="Средний"');
        $this->addSql('UPDATE priorities SET class="c-badge--danger", table_class="c-table__row--danger" WHERE name="Высокий"');

        $this->addSql('UPDATE statuses SET class="u-color-success" WHERE name="Новая"');
        $this->addSql('UPDATE statuses SET class="u-color-info" WHERE name="В работе"');
        $this->addSql('UPDATE statuses SET class="u-color-warning" WHERE name="Ожидает доработки"');
        $this->addSql('UPDATE statuses SET class="u-color-warning" WHERE name="Тестирование"');
        $this->addSql('UPDATE statuses SET class="u-color-warning" WHERE name="Ожидает проверки"');
        $this->addSql('UPDATE statuses SET class="u-color-info" WHERE name="На проверке"');
        $this->addSql('UPDATE statuses SET class="u-color-warning" WHERE name="Запрос данных"');
        $this->addSql('UPDATE statuses SET class="u-color-warning" WHERE name="Согласование"');
        $this->addSql('UPDATE statuses SET class="u-color-primary" WHERE name="Отменено"');
        $this->addSql('UPDATE statuses SET class="u-color-primary" WHERE name="Завершено"');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE priorities DROP class, DROP table_class');
        $this->addSql('ALTER TABLE statuses DROP class');
    }
}
