<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260716123054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add relation between messages and rooms';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__messages AS SELECT id, content, created_at, updated_at FROM messages');
        $this->addSql('DROP TABLE messages');
        $this->addSql('CREATE TABLE messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, room_id INTEGER NOT NULL, CONSTRAINT FK_DB021E9654177093 FOREIGN KEY (room_id) REFERENCES rooms (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO messages (id, content, created_at, updated_at) SELECT id, content, created_at, updated_at FROM __temp__messages');
        $this->addSql('DROP TABLE __temp__messages');
        $this->addSql('CREATE INDEX IDX_DB021E9654177093 ON messages (room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__messages AS SELECT id, content, created_at, updated_at FROM messages');
        $this->addSql('DROP TABLE messages');
        $this->addSql('CREATE TABLE messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO messages (id, content, created_at, updated_at) SELECT id, content, created_at, updated_at FROM __temp__messages');
        $this->addSql('DROP TABLE __temp__messages');
    }
}
