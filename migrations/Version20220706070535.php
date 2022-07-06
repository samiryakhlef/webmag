<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706070535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscriber (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, activated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_AD005B69A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscriber ADD CONSTRAINT FK_AD005B69A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('DROP TABLE profil');
        $this->addSql('ALTER TABLE user ADD subscription_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499A1887DC FOREIGN KEY (subscription_id) REFERENCES subscriber (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6499A1887DC ON user (subscription_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6499A1887DC');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, images VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, videos VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_E6D6B297A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE subscriber');
        $this->addSql('DROP INDEX UNIQ_8D93D6499A1887DC ON `user`');
        $this->addSql('ALTER TABLE `user` DROP subscription_id');
    }
}
