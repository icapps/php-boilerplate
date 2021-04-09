<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409152723 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE icapps_devices (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, device_id VARCHAR(255) NOT NULL, device_token VARCHAR(255) NOT NULL, INDEX IDX_32D6C7ACA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE icapps_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, enabled TINYINT(1) DEFAULT \'0\' NOT NULL, activation_token VARCHAR(255) DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, language VARCHAR(2) DEFAULT \'nl\' NOT NULL, UNIQUE INDEX UNIQ_EACC5C5EE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE icapps_devices ADD CONSTRAINT FK_32D6C7ACA76ED395 FOREIGN KEY (user_id) REFERENCES icapps_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE icapps_devices DROP FOREIGN KEY FK_32D6C7ACA76ED395');
        $this->addSql('DROP TABLE icapps_devices');
        $this->addSql('DROP TABLE icapps_user');
    }
}
