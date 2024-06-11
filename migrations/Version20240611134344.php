<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240611134344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organisation CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE address address LONGTEXT DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE keywords keywords LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE links links LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE slug slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE volunteer CHANGE last_na�me last_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organisation CHANGE name name VARCHAR(255) NOT NULL, CHANGE address address LONGTEXT NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE keywords keywords LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE links links LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE volunteer CHANGE last_name last_na�me VARCHAR(255) NOT NULL');
    }
}
