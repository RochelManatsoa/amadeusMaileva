<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221115082903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE envoi (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, custom_id INT NOT NULL, custom_data VARCHAR(255) NOT NULL, acknowledgement_of_receipt TINYINT(1) NOT NULL, acknowledgement_of_receipt_scanning TINYINT(1) NOT NULL, color_printing TINYINT(1) NOT NULL, duplex_printing TINYINT(1) NOT NULL, optionnal_address_sheet TINYINT(1) NOT NULL, notification_email VARCHAR(255) NOT NULL, senders_address_line1 VARCHAR(255) DEFAULT NULL, senders_address_line2 VARCHAR(255) DEFAULT NULL, senders_address_line3 VARCHAR(255) DEFAULT NULL, senders_address_line4 VARCHAR(255) DEFAULT NULL, senders_address_line5 VARCHAR(255) DEFAULT NULL, senders_address_line6 VARCHAR(255) DEFAULT NULL, sender_country_code VARCHAR(255) DEFAULT NULL, archiving_duration INT DEFAULT NULL, return_envelope_reference VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, status_detail VARCHAR(255) DEFAULT NULL, document_count INT DEFAULT NULL, document_weight INT DEFAULT NULL, page_count INT DEFAULT NULL, billet_pages_count INT DEFAULT NULL, sheet_count INT DEFAULT NULL, creation_date DATETIME DEFAULT NULL, submission_date DATETIME DEFAULT NULL, scheduled_date DATETIME DEFAULT NULL, processed_date DATETIME DEFAULT NULL, archive_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE envoi');
    }
}
