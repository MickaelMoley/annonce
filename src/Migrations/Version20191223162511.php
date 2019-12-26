<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191223162511 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dealer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, dealer_id INT NOT NULL, phone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5249E6EA1 FOREIGN KEY (dealer_id) REFERENCES dealer (id)');
        $this->addSql('CREATE INDEX IDX_F65593E5249E6EA1 ON annonce (dealer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5249E6EA1');
        $this->addSql('DROP TABLE dealer');
        $this->addSql('DROP INDEX IDX_F65593E5249E6EA1 ON annonce');
    }
}
