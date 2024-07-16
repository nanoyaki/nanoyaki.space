<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716210329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          user
        DROP
          INDEX UNIQ_8D93D649292E8AE2,
        ADD
          INDEX IDX_8D93D649292E8AE2 (profile_picture_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          user
        DROP
          INDEX IDX_8D93D649292E8AE2,
        ADD
          UNIQUE INDEX UNIQ_8D93D649292E8AE2 (profile_picture_id)');
    }
}
