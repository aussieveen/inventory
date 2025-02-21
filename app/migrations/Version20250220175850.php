<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220175850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, parent_id INT DEFAULT NULL, INDEX IDX_64C19C1727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE container (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(12) NOT NULL, zone_id INT NOT NULL, INDEX IDX_C7A2EC1B9F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) DEFAULT NULL, code VARCHAR(12) DEFAULT NULL, quantity INT NOT NULL, container_id INT DEFAULT NULL, zone_id INT DEFAULT NULL, category_id INT NOT NULL, INDEX IDX_1F1B251EBC21F742 (container_id), INDEX IDX_1F1B251E9F2C3FAB (zone_id), INDEX IDX_1F1B251E12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1B9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EBC21F742 FOREIGN KEY (container_id) REFERENCES container (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1B9F2C3FAB');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EBC21F742');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E9F2C3FAB');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E12469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE container');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE zone');
    }
}
