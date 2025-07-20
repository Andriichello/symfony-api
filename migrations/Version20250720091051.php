<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250720091051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author_genre (author_id INT NOT NULL, genre_id INT NOT NULL, PRIMARY KEY (author_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_95D81335F675F31B ON author_genre (author_id)');
        $this->addSql('CREATE INDEX IDX_95D813354296D31F ON author_genre (genre_id)');
        $this->addSql('ALTER TABLE author_genre ADD CONSTRAINT FK_95D81335F675F31B FOREIGN KEY (author_id) REFERENCES authors (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE author_genre ADD CONSTRAINT FK_95D813354296D31F FOREIGN KEY (genre_id) REFERENCES genres (id) NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author_genre DROP CONSTRAINT FK_95D81335F675F31B');
        $this->addSql('ALTER TABLE author_genre DROP CONSTRAINT FK_95D813354296D31F');
        $this->addSql('DROP TABLE author_genre');
    }
}
