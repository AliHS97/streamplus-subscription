<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250313211817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payments CHANGE expiration_date expiration_date VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE payments RENAME INDEX uniq_6d28840da76ed395 TO UNIQ_65D29B32A76ED395');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payments CHANGE expiration_date expiration_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payments RENAME INDEX uniq_65d29b32a76ed395 TO UNIQ_6D28840DA76ED395');
    }
}
