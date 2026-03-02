<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260302162939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commande_client (id INT AUTO_INCREMENT NOT NULL, date_commande DATETIME NOT NULL, user_id_id INT NOT NULL, statut_commande_id_id INT NOT NULL, INDEX IDX_C510FF809D86650F (user_id_id), INDEX IDX_C510FF803B9D167B (statut_commande_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE contenir (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, produit_id INT NOT NULL, commande_client_id INT NOT NULL, INDEX IDX_3C914DFDF347EFB (produit_id), INDEX IDX_3C914DFD9E73363 (commande_client_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE credential (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_57F1D4BA76ED395 (user_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE enfant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, user_id_id INT NOT NULL, INDEX IDX_34B70CA29D86650F (user_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE entreposer (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, categorie VARCHAR(255) NOT NULL, produit_id INT NOT NULL, entrepot_id INT NOT NULL, INDEX IDX_21906394F347EFB (produit_id), INDEX IDX_2190639472831E97 (entrepot_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE entrepot (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, web TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, url LONGTEXT DEFAULT NULL, produit_id INT DEFAULT NULL, INDEX IDX_C53D045FF347EFB (produit_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, prix NUMERIC(10, 2) NOT NULL, reference VARCHAR(255) NOT NULL, fournisseur VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, categorie_id INT NOT NULL, INDEX IDX_29A5EC27BCF5E72D (categorie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE produit_categorie (produit_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_CDEA88D8F347EFB (produit_id), INDEX IDX_CDEA88D8BCF5E72D (categorie_id), PRIMARY KEY (produit_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sport (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE statut_commande (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(512) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, date_naissance DATE DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_sport (user_id INT NOT NULL, sport_id INT NOT NULL, INDEX IDX_F847148AA76ED395 (user_id), INDEX IDX_F847148AAC78BCF8 (sport_id), PRIMARY KEY (user_id, sport_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE commande_client ADD CONSTRAINT FK_C510FF809D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande_client ADD CONSTRAINT FK_C510FF803B9D167B FOREIGN KEY (statut_commande_id_id) REFERENCES statut_commande (id)');
        $this->addSql('ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFDF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFD9E73363 FOREIGN KEY (commande_client_id) REFERENCES commande_client (id)');
        $this->addSql('ALTER TABLE credential ADD CONSTRAINT FK_57F1D4BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE enfant ADD CONSTRAINT FK_34B70CA29D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE entreposer ADD CONSTRAINT FK_21906394F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE entreposer ADD CONSTRAINT FK_2190639472831E97 FOREIGN KEY (entrepot_id) REFERENCES entrepot (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE produit_categorie ADD CONSTRAINT FK_CDEA88D8F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_categorie ADD CONSTRAINT FK_CDEA88D8BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_sport ADD CONSTRAINT FK_F847148AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_sport ADD CONSTRAINT FK_F847148AAC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_client DROP FOREIGN KEY FK_C510FF809D86650F');
        $this->addSql('ALTER TABLE commande_client DROP FOREIGN KEY FK_C510FF803B9D167B');
        $this->addSql('ALTER TABLE contenir DROP FOREIGN KEY FK_3C914DFDF347EFB');
        $this->addSql('ALTER TABLE contenir DROP FOREIGN KEY FK_3C914DFD9E73363');
        $this->addSql('ALTER TABLE credential DROP FOREIGN KEY FK_57F1D4BA76ED395');
        $this->addSql('ALTER TABLE enfant DROP FOREIGN KEY FK_34B70CA29D86650F');
        $this->addSql('ALTER TABLE entreposer DROP FOREIGN KEY FK_21906394F347EFB');
        $this->addSql('ALTER TABLE entreposer DROP FOREIGN KEY FK_2190639472831E97');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FF347EFB');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE produit_categorie DROP FOREIGN KEY FK_CDEA88D8F347EFB');
        $this->addSql('ALTER TABLE produit_categorie DROP FOREIGN KEY FK_CDEA88D8BCF5E72D');
        $this->addSql('ALTER TABLE user_sport DROP FOREIGN KEY FK_F847148AA76ED395');
        $this->addSql('ALTER TABLE user_sport DROP FOREIGN KEY FK_F847148AAC78BCF8');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande_client');
        $this->addSql('DROP TABLE contenir');
        $this->addSql('DROP TABLE credential');
        $this->addSql('DROP TABLE enfant');
        $this->addSql('DROP TABLE entreposer');
        $this->addSql('DROP TABLE entrepot');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_categorie');
        $this->addSql('DROP TABLE sport');
        $this->addSql('DROP TABLE statut_commande');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_sport');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
