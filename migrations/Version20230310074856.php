<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310074856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rh ADD id_company_id INT DEFAULT NULL, DROP id_company');
        $this->addSql('ALTER TABLE rh ADD CONSTRAINT FK_1FB9E0E132119A01 FOREIGN KEY (id_company_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1FB9E0E132119A01 ON rh (id_company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rh DROP FOREIGN KEY FK_1FB9E0E132119A01');
        $this->addSql('DROP INDEX IDX_1FB9E0E132119A01 ON rh');
        $this->addSql('ALTER TABLE rh ADD id_company INT NOT NULL, DROP id_company_id');
    }
}
