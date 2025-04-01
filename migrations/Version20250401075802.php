<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250401075802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_workshop (user_id INT NOT NULL, workshop_id INT NOT NULL, INDEX IDX_7BE2E6C4A76ED395 (user_id), INDEX IDX_7BE2E6C41FDCE57C (workshop_id), PRIMARY KEY(user_id, workshop_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_workshop ADD CONSTRAINT FK_7BE2E6C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_workshop ADD CONSTRAINT FK_7BE2E6C41FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user_workshop DROP FOREIGN KEY FK_7BE2E6C4A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_workshop DROP FOREIGN KEY FK_7BE2E6C41FDCE57C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_workshop
        SQL);
    }
}
