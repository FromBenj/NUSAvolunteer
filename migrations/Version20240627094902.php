<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627094902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matching (id INT AUTO_INCREMENT NOT NULL, organisation_id INT NOT NULL, volunteer_id INT NOT NULL, INDEX IDX_DC10F2899E6B1585 (organisation_id), INDEX IDX_DC10F2898EFAB6B1 (volunteer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matching ADD CONSTRAINT FK_DC10F2899E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id)');
        $this->addSql('ALTER TABLE matching ADD CONSTRAINT FK_DC10F2898EFAB6B1 FOREIGN KEY (volunteer_id) REFERENCES volunteer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matching DROP FOREIGN KEY FK_DC10F2899E6B1585');
        $this->addSql('ALTER TABLE matching DROP FOREIGN KEY FK_DC10F2898EFAB6B1');
        $this->addSql('DROP TABLE matching');
    }
}
