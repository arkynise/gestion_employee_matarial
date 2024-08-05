<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240805112015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modefication ADD CONSTRAINT FK_950D6C687B159B1 FOREIGN KEY (numero_eqp_id) REFERENCES equipment (numeroEqp)');
        $this->addSql('ALTER TABLE modefication ADD CONSTRAINT FK_950D6C6ED766068 FOREIGN KEY (username_id) REFERENCES user (username)');
        $this->addSql('ALTER TABLE reparation ADD CONSTRAINT FK_8FDF219D87B159B1 FOREIGN KEY (numero_eqp_id) REFERENCES equipment (numeroEqp)');
        $this->addSql('ALTER TABLE reparation RENAME INDEX numero_eqp_id TO IDX_8FDF219D87B159B1');
        $this->addSql('ALTER TABLE user CHANGE count_ntf count_ntf INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisation DROP FOREIGN KEY utilisation_ibfk_2');
        $this->addSql('ALTER TABLE utilisation ADD CONSTRAINT FK_B02A3C4387B159B1 FOREIGN KEY (numero_eqp_id) REFERENCES equipment (numeroEqp)');
        $this->addSql('ALTER TABLE utilisation RENAME INDEX numero_eqp_id TO IDX_B02A3C4387B159B1');
        $this->addSql('ALTER TABLE utilisation RENAME INDEX id_emp_id TO IDX_B02A3C435C5185E5');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modefication DROP FOREIGN KEY FK_950D6C687B159B1');
        $this->addSql('ALTER TABLE modefication DROP FOREIGN KEY FK_950D6C6ED766068');
        $this->addSql('ALTER TABLE reparation DROP FOREIGN KEY FK_8FDF219D87B159B1');
        $this->addSql('ALTER TABLE reparation RENAME INDEX idx_8fdf219d87b159b1 TO numero_eqp_id');
        $this->addSql('ALTER TABLE `user` CHANGE count_ntf count_ntf INT DEFAULT 0');
        $this->addSql('ALTER TABLE utilisation DROP FOREIGN KEY FK_B02A3C4387B159B1');
        $this->addSql('ALTER TABLE utilisation ADD CONSTRAINT utilisation_ibfk_2 FOREIGN KEY (numero_eqp_id) REFERENCES equipment (numeroEqp)');
        $this->addSql('ALTER TABLE utilisation RENAME INDEX idx_b02a3c4387b159b1 TO numero_eqp_id');
        $this->addSql('ALTER TABLE utilisation RENAME INDEX idx_b02a3c435c5185e5 TO id_emp_id');
    }
}
