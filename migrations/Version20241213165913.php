<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241213165913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formation_etudiant (formation_id INT NOT NULL, etudiant_id INT NOT NULL, PRIMARY KEY(formation_id, etudiant_id))');
        $this->addSql('CREATE INDEX IDX_B6EC75125200282E ON formation_etudiant (formation_id)');
        $this->addSql('CREATE INDEX IDX_B6EC7512DDEAB1A3 ON formation_etudiant (etudiant_id)');
        $this->addSql('ALTER TABLE formation_etudiant ADD CONSTRAINT FK_B6EC75125200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE formation_etudiant ADD CONSTRAINT FK_B6EC7512DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE formation_etudiant DROP CONSTRAINT FK_B6EC75125200282E');
        $this->addSql('ALTER TABLE formation_etudiant DROP CONSTRAINT FK_B6EC7512DDEAB1A3');
        $this->addSql('DROP TABLE formation_etudiant');
    }
}
