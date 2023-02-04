<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230109203015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, stripe_transaction_id INT DEFAULT NULL, payment_intent VARCHAR(255) DEFAULT NULL, custom_id VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, type VARCHAR(255) DEFAULT NULL, sended_at DATETIME NOT NULL, option1 TINYINT(1) DEFAULT NULL, option2 TINYINT(1) DEFAULT NULL, option3 TINYINT(1) DEFAULT NULL, banked_at DATETIME DEFAULT NULL, order_id VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_F5299398838CF133 (stripe_transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stripe_transaction (id INT AUTO_INCREMENT NOT NULL, intent_id VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, amount_capturable INT DEFAULT NULL, amount_details VARCHAR(255) DEFAULT NULL, amount_received INT DEFAULT NULL, capture_method VARCHAR(255) DEFAULT NULL, client_secret VARCHAR(255) DEFAULT NULL, confirmation_method VARCHAR(255) DEFAULT NULL, created VARCHAR(255) DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, customer VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, invoice VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398838CF133 FOREIGN KEY (stripe_transaction_id) REFERENCES stripe_transaction (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398838CF133');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE stripe_transaction');
    }
}
