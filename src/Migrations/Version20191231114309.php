<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191231114309 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, vehicle_id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, url LONGTEXT DEFAULT NULL, make VARCHAR(255) NOT NULL, model VARCHAR(255) DEFAULT NULL, image LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', year INT NOT NULL, mileage VARCHAR(255) NOT NULL, drivetrain VARCHAR(255) NOT NULL, vehicle_registration_plate VARCHAR(255) NOT NULL, body_style VARCHAR(255) NOT NULL, fuel_type VARCHAR(255) NOT NULL, transmission VARCHAR(255) NOT NULL, price INT NOT NULL, features LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', latitude VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, exterior_color VARCHAR(255) NOT NULL, state_of_vehicle VARCHAR(255) NOT NULL, fb_page_id VARCHAR(255) NOT NULL, dealer_communication_channel VARCHAR(255) NOT NULL, dealer_privacy_policy_url VARCHAR(255) NOT NULL, dealer_id INT NOT NULL, adress LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', slug VARCHAR(255) NOT NULL, dealer_name VARCHAR(255) NOT NULL, dealer_phone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE dealer');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dealer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, dealer_id INT NOT NULL, phone VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE annonce');
    }
}
