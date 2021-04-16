<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210415152348 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE icapps_devices (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, device_id VARCHAR(255) NOT NULL, device_token VARCHAR(255) NOT NULL, INDEX IDX_32D6C7ACA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE icapps_profiles (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, changed DATETIME NOT NULL, idUsersCreator INT DEFAULT NULL, idUsersChanger INT DEFAULT NULL, INDEX IDX_44A9C120DBF11E1D (idUsersCreator), INDEX IDX_44A9C12030D07CD5 (idUsersChanger), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE icapps_users (id INT AUTO_INCREMENT NOT NULL, profile_id INT NOT NULL, email VARCHAR(50) NOT NULL, pending_email VARCHAR(50) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, activation_token VARCHAR(255) DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, language VARCHAR(2) DEFAULT \'nl\' NOT NULL, enabled TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX UNIQ_97377FA4CCFA12B8 (profile_id), UNIQUE INDEX user_unique (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE icapps_devices ADD CONSTRAINT FK_32D6C7ACA76ED395 FOREIGN KEY (user_id) REFERENCES icapps_users (id)');
        $this->addSql('ALTER TABLE icapps_profiles ADD CONSTRAINT FK_44A9C120DBF11E1D FOREIGN KEY (idUsersCreator) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE icapps_profiles ADD CONSTRAINT FK_44A9C12030D07CD5 FOREIGN KEY (idUsersChanger) REFERENCES se_users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE icapps_users ADD CONSTRAINT FK_97377FA4CCFA12B8 FOREIGN KEY (profile_id) REFERENCES icapps_profiles (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE icapps_users DROP FOREIGN KEY FK_97377FA4CCFA12B8');
        $this->addSql('ALTER TABLE icapps_devices DROP FOREIGN KEY FK_32D6C7ACA76ED395');
        $this->addSql('DROP TABLE icapps_devices');
        $this->addSql('DROP TABLE icapps_profiles');
        $this->addSql('DROP TABLE icapps_users');
    }
}
