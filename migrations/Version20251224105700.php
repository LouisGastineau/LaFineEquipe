<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add user profile fields: avatar, description, phone, address
 */
final class Version20251224105700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user profile fields for avatar, description, phone, and address';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD avatar VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD address VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP avatar');
        $this->addSql('ALTER TABLE user DROP description');
        $this->addSql('ALTER TABLE user DROP phone');
        $this->addSql('ALTER TABLE user DROP address');
    }
}
