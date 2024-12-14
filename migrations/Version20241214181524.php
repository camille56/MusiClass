<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241214181524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_formation (user_id INT NOT NULL, formation_id INT NOT NULL, PRIMARY KEY(user_id, formation_id))');
        $this->addSql('CREATE INDEX IDX_40A0AC5BA76ED395 ON user_formation (user_id)');
        $this->addSql('CREATE INDEX IDX_40A0AC5B5200282E ON user_formation (formation_id)');
        $this->addSql('ALTER TABLE user_formation ADD CONSTRAINT FK_40A0AC5BA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_formation ADD CONSTRAINT FK_40A0AC5B5200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD prenom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD date_inscription TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD telephone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD commentaire TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD instrument VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN "user".date_inscription IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_formation DROP CONSTRAINT FK_40A0AC5BA76ED395');
        $this->addSql('ALTER TABLE user_formation DROP CONSTRAINT FK_40A0AC5B5200282E');
        $this->addSql('DROP TABLE user_formation');
        $this->addSql('ALTER TABLE "user" DROP nom');
        $this->addSql('ALTER TABLE "user" DROP prenom');
        $this->addSql('ALTER TABLE "user" DROP date_inscription');
        $this->addSql('ALTER TABLE "user" DROP telephone');
        $this->addSql('ALTER TABLE "user" DROP commentaire');
        $this->addSql('ALTER TABLE "user" DROP instrument');
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(180)');
    }
}
