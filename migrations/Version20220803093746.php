<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220803093746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_cart (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, quantity INT NOT NULL, UNIQUE INDEX UNIQ_CA516ECCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_cart ADD CONSTRAINT FK_CA516ECCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD shop_cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD19E68D95 FOREIGN KEY (shop_cart_id) REFERENCES shop_cart (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD19E68D95 ON product (shop_cart_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD19E68D95');
        $this->addSql('DROP TABLE shop_cart');
        $this->addSql('DROP INDEX IDX_D34A04AD19E68D95 ON product');
        $this->addSql('ALTER TABLE product DROP shop_cart_id');
    }
}
