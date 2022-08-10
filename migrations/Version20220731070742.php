<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220731070742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, keywords VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, companyname VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone VARCHAR(30) NOT NULL, fax VARCHAR(30) NOT NULL, email VARCHAR(75) NOT NULL, smtpserver VARCHAR(100) DEFAULT NULL, smtpemail VARCHAR(100) DEFAULT NULL, smtppassword VARCHAR(50) DEFAULT NULL, smtpport VARCHAR(10) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, instragram VARCHAR(255) DEFAULT NULL, twitter VARCHAR(100) DEFAULT NULL, about VARCHAR(255) NOT NULL, contact LONGTEXT DEFAULT NULL, reference LONGTEXT DEFAULT NULL, status VARCHAR(6) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE setting');
    }
}
