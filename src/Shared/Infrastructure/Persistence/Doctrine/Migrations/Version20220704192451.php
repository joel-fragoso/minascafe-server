<?php

declare(strict_types=1);

namespace Minascafe\Shared\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220704192451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_tokens (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', token CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, userId CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_CF080AB364B64DCC (userId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_tokens ADD CONSTRAINT FK_CF080AB364B64DCC FOREIGN KEY (userId) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_tokens');
    }
}
