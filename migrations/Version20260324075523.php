<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260324075523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entreposer (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, categorie VARCHAR(255) NOT NULL, produit_id INT NOT NULL, entrepot_id INT NOT NULL, INDEX IDX_21906394F347EFB (produit_id), INDEX IDX_2190639472831E97 (entrepot_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE entrepot (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, web TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE entreposer ADD CONSTRAINT FK_21906394F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE entreposer ADD CONSTRAINT FK_2190639472831E97 FOREIGN KEY (entrepot_id) REFERENCES entrepot (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreposer DROP FOREIGN KEY FK_21906394F347EFB');
        $this->addSql('ALTER TABLE entreposer DROP FOREIGN KEY FK_2190639472831E97');
        $this->addSql('DROP TABLE entreposer');
        $this->addSql('DROP TABLE entrepot');
    }
}
