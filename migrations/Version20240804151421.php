<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240804151421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipment (numeroEqp INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, date_ajoute DATE NOT NULL, description LONGTEXT DEFAULT NULL, model VARCHAR(255) NOT NULL, type VARCHAR(34) NOT NULL, image VARCHAR(255) NOT NULL, state VARCHAR(20) NOT NULL, PRIMARY KEY(numeroEqp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reparations (id INT AUTO_INCREMENT NOT NULL, numero_eqp_id INT NOT NULL, date_entre DATE NOT NULL, date_sortie DATE DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_953FFFD387B159B1 (numero_eqp_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reparations ADD CONSTRAINT FK_953FFFD387B159B1 FOREIGN KEY (numero_eqp_id) REFERENCES equipment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reparations DROP FOREIGN KEY FK_953FFFD387B159B1');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE reparations');
    }
}
