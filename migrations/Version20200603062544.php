<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200603062544 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, uuid VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, deposee_le DATETIME NOT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_5FB6DEC71E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_choix_possible (reponse_id INT NOT NULL, choix_possible_id INT NOT NULL, INDEX IDX_B2A4EE6ACF18BB82 (reponse_id), INDEX IDX_B2A4EE6A9EF03129 (choix_possible_id), PRIMARY KEY(reponse_id, choix_possible_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE choix_possible (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, texte VARCHAR(255) NOT NULL, INDEX IDX_575A945E1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, auteur VARCHAR(255) NOT NULL, interrogation LONGTEXT NOT NULL, choix_multiple TINYINT(1) NOT NULL, reponses_publiques TINYINT(1) NOT NULL, titre VARCHAR(255) NOT NULL, est_razquotidien TINYINT(1) NOT NULL, posee_le DATETIME NOT NULL, reponses_anonymes TINYINT(1) NOT NULL, est_visible TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC71E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE reponse_choix_possible ADD CONSTRAINT FK_B2A4EE6ACF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_choix_possible ADD CONSTRAINT FK_B2A4EE6A9EF03129 FOREIGN KEY (choix_possible_id) REFERENCES choix_possible (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE choix_possible ADD CONSTRAINT FK_575A945E1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reponse_choix_possible DROP FOREIGN KEY FK_B2A4EE6ACF18BB82');
        $this->addSql('ALTER TABLE reponse_choix_possible DROP FOREIGN KEY FK_B2A4EE6A9EF03129');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC71E27F6BF');
        $this->addSql('ALTER TABLE choix_possible DROP FOREIGN KEY FK_575A945E1E27F6BF');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reponse_choix_possible');
        $this->addSql('DROP TABLE choix_possible');
        $this->addSql('DROP TABLE question');
    }
}
