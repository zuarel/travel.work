<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220115124533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD invited_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7C2ED4747 FOREIGN KEY (invited_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7C2ED4747 ON event (invited_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7C2ED4747');
        $this->addSql('DROP INDEX IDX_3BAE0AA7C2ED4747 ON event');
        $this->addSql('ALTER TABLE event DROP invited_id');
    }
}
