<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329145542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, ref INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club_student (club_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_BA6B0AB161190A32 (club_id), INDEX IDX_BA6B0AB1CB944F1A (student_id), PRIMARY KEY(club_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE club_student ADD CONSTRAINT FK_BA6B0AB161190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE club_student ADD CONSTRAINT FK_BA6B0AB1CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club_student DROP FOREIGN KEY FK_BA6B0AB161190A32');
        $this->addSql('ALTER TABLE club_student DROP FOREIGN KEY FK_BA6B0AB1CB944F1A');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE club_student');
    }
}
