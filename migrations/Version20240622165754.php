<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240622165754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, digest VARCHAR(2048) DEFAULT NULL, is_pinned TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_images (post_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_D03D5A0F4B89032C (post_id), UNIQUE INDEX UNIQ_D03D5A0F3DA5256D (image_id), PRIMARY KEY(post_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_images ADD CONSTRAINT FK_D03D5A0F4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_images ADD CONSTRAINT FK_D03D5A0F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('DROP TABLE blog_post');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_post (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified DATETIME DEFAULT NULL, is_pinned TINYINT(1) NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, content TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE post_images DROP FOREIGN KEY FK_D03D5A0F4B89032C');
        $this->addSql('ALTER TABLE post_images DROP FOREIGN KEY FK_D03D5A0F3DA5256D');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_images');
    }
}
