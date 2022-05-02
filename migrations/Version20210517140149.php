<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517140149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image_bricosphere (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bricosphere ADD image_bricosphere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bricosphere ADD CONSTRAINT FK_A6E2A03F3EE38FEC FOREIGN KEY (image_bricosphere_id) REFERENCES image_bricosphere (id)');
        $this->addSql('CREATE INDEX IDX_A6E2A03F3EE38FEC ON bricosphere (image_bricosphere_id)');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FA9755886');
        $this->addSql('DROP INDEX IDX_C53D045FA9755886 ON image');
        $this->addSql('ALTER TABLE image DROP bricosphere_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bricosphere DROP FOREIGN KEY FK_A6E2A03F3EE38FEC');
        $this->addSql('DROP TABLE image_bricosphere');
        $this->addSql('DROP INDEX IDX_A6E2A03F3EE38FEC ON bricosphere');
        $this->addSql('ALTER TABLE bricosphere DROP image_bricosphere_id');
        $this->addSql('ALTER TABLE image ADD bricosphere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FA9755886 FOREIGN KEY (bricosphere_id) REFERENCES bricosphere (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FA9755886 ON image (bricosphere_id)');
    }
}
