<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210511195506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bricosphere ADD creator_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bricosphere ADD CONSTRAINT FK_A6E2A03F61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A6E2A03F61220EA6 ON bricosphere (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bricosphere DROP FOREIGN KEY FK_A6E2A03F61220EA6');
        $this->addSql('DROP INDEX IDX_A6E2A03F61220EA6 ON bricosphere');
        $this->addSql('ALTER TABLE bricosphere DROP creator_id');
    }
}
