<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240628193600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email_confirmation (
          id INT AUTO_INCREMENT NOT NULL,
          is_confirmed TINYINT(1) NOT NULL,
          valid_until DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          token VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (
          id INT AUTO_INCREMENT NOT NULL,
          user_id INT DEFAULT NULL,
          upload_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          path VARCHAR(255) NOT NULL,
          description VARCHAR(255) NOT NULL,
          INDEX IDX_C53D045FA76ED395 (user_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (
          id INT AUTO_INCREMENT NOT NULL,
          user_id INT DEFAULT NULL,
          created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          modified DATETIME DEFAULT NULL,
          title VARCHAR(255) NOT NULL,
          content TEXT NOT NULL,
          digest VARCHAR(2048) DEFAULT NULL,
          is_pinned TINYINT(1) NOT NULL,
          INDEX IDX_5A8A6C8DA76ED395 (user_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_images (
          post_id INT NOT NULL,
          image_id INT NOT NULL,
          INDEX IDX_D03D5A0F4B89032C (post_id),
          UNIQUE INDEX UNIQ_D03D5A0F3DA5256D (image_id),
          PRIMARY KEY(post_id, image_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (
          id INT AUTO_INCREMENT NOT NULL,
          email_confirmation_id INT DEFAULT NULL,
          PASSWORD VARCHAR(255) NOT NULL,
          username VARCHAR(32) NOT NULL,
          email VARCHAR(180) NOT NULL,
          roles JSON NOT NULL COMMENT \'(DC2Type:json)\',
          UNIQUE INDEX UNIQ_8D93D64989CC6046 (email_confirmation_id),
          UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (
          id BIGINT AUTO_INCREMENT NOT NULL,
          body LONGTEXT NOT NULL,
          headers LONGTEXT NOT NULL,
          queue_name VARCHAR(190) NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          INDEX IDX_75EA56E0FB7336F0 (queue_name),
          INDEX IDX_75EA56E0E3BD61CE (available_at),
          INDEX IDX_75EA56E016BA31DB (delivered_at),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          image
        ADD
          CONSTRAINT FK_C53D045FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE
          post_images
        ADD
          CONSTRAINT FK_D03D5A0F4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE
          post_images
        ADD
          CONSTRAINT FK_D03D5A0F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE
          user
        ADD
          CONSTRAINT FK_8D93D64989CC6046 FOREIGN KEY (email_confirmation_id) REFERENCES email_confirmation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FA76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE post_images DROP FOREIGN KEY FK_D03D5A0F4B89032C');
        $this->addSql('ALTER TABLE post_images DROP FOREIGN KEY FK_D03D5A0F3DA5256D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64989CC6046');
        $this->addSql('DROP TABLE email_confirmation');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_images');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
