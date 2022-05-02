<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210505082053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_bricosphere');
        $this->addSql('DROP TABLE user_talent');
        $this->addSql('ALTER TABLE bricosphere ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bricosphere ADD CONSTRAINT FK_A6E2A03F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE INDEX IDX_A6E2A03F3DA5256D ON bricosphere (image_id)');
        $this->addSql('ALTER TABLE image ADD blog_article_id INT DEFAULT NULL, ADD tool_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F9452A475 FOREIGN KEY (blog_article_id) REFERENCES blog_article (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F8F7B22CC FOREIGN KEY (tool_id) REFERENCES tool (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F9452A475 ON image (blog_article_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F8F7B22CC ON image (tool_id)');
        $this->addSql('ALTER TABLE tool DROP FOREIGN KEY FK_20F33ED1A76ED395');
        $this->addSql('DROP INDEX IDX_20F33ED1A76ED395 ON tool');
        $this->addSql('ALTER TABLE tool DROP user_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64986383B10');
        $this->addSql('DROP INDEX IDX_8D93D64986383B10 ON user');
        $this->addSql('ALTER TABLE user ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', DROP avatar_id, CHANGE email email VARCHAR(180) NOT NULL, CHANGE adress address VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_bricosphere (user_id INT NOT NULL, bricosphere_id INT NOT NULL, INDEX IDX_FF969AAA9755886 (bricosphere_id), INDEX IDX_FF969AAA76ED395 (user_id), PRIMARY KEY(user_id, bricosphere_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_talent (user_id INT NOT NULL, talent_id INT NOT NULL, INDEX IDX_738B19C818777CEF (talent_id), INDEX IDX_738B19C8A76ED395 (user_id), PRIMARY KEY(user_id, talent_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_bricosphere ADD CONSTRAINT FK_FF969AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_bricosphere ADD CONSTRAINT FK_FF969AAA9755886 FOREIGN KEY (bricosphere_id) REFERENCES bricosphere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_talent ADD CONSTRAINT FK_738B19C818777CEF FOREIGN KEY (talent_id) REFERENCES talent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_talent ADD CONSTRAINT FK_738B19C8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bricosphere DROP FOREIGN KEY FK_A6E2A03F3DA5256D');
        $this->addSql('DROP INDEX IDX_A6E2A03F3DA5256D ON bricosphere');
        $this->addSql('ALTER TABLE bricosphere DROP image_id');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F9452A475');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F8F7B22CC');
        $this->addSql('DROP INDEX IDX_C53D045F9452A475 ON image');
        $this->addSql('DROP INDEX IDX_C53D045F8F7B22CC ON image');
        $this->addSql('ALTER TABLE image DROP blog_article_id, DROP tool_id');
        $this->addSql('ALTER TABLE tool ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tool ADD CONSTRAINT FK_20F33ED1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_20F33ED1A76ED395 ON tool (user_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD avatar_id INT DEFAULT NULL, DROP roles, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address adress VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64986383B10 ON user (avatar_id)');
    }
}
