<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220901143004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participaciones (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, pool_id INT NOT NULL, fecha DATETIME NOT NULL, monto DOUBLE PRECISION NOT NULL, status INT NOT NULL, INDEX IDX_B769E473A76ED395 (user_id), INDEX IDX_B769E4737B3406DF (pool_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participaciones ADD CONSTRAINT FK_B769E473A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participaciones ADD CONSTRAINT FK_B769E4737B3406DF FOREIGN KEY (pool_id) REFERENCES pool (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE participaciones');
    }
}
