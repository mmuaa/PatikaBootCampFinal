<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220809091339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE setting DROP smtpserver, DROP smtpemail, DROP smtppassword, DROP smtpport');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE setting ADD smtpserver VARCHAR(100) DEFAULT NULL, ADD smtpemail VARCHAR(100) DEFAULT NULL, ADD smtppassword VARCHAR(50) DEFAULT NULL, ADD smtpport VARCHAR(10) DEFAULT NULL');
    }
}
