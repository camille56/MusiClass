<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241213165544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP CONSTRAINT fk_404021bfddeab1a3');
        $this->addSql('DROP INDEX idx_404021bfddeab1a3');
        $this->addSql('ALTER TABLE formation DROP etudiant_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE formation ADD etudiant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT fk_404021bfddeab1a3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_404021bfddeab1a3 ON formation (etudiant_id)');
    }
}
