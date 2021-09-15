<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210915094826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE idea_like (id INT AUTO_INCREMENT NOT NULL, idea_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_6F2AF5185B6FEF7D (idea_id), INDEX IDX_6F2AF518A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE idea_like ADD CONSTRAINT FK_6F2AF5185B6FEF7D FOREIGN KEY (idea_id) REFERENCES idea (id)');
        $this->addSql('ALTER TABLE idea_like ADD CONSTRAINT FK_6F2AF518A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE idea_like');
    }
}
