<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170924080456 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE saved_lesson ADD sentence_id INT NOT NULL, DROP sentences');
        $this->addSql('ALTER TABLE saved_lesson ADD CONSTRAINT FK_9C720FE827289490 FOREIGN KEY (sentence_id) REFERENCES sentence (id)');
        $this->addSql('CREATE INDEX IDX_9C720FE827289490 ON saved_lesson (sentence_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE saved_lesson DROP FOREIGN KEY FK_9C720FE827289490');
        $this->addSql('DROP INDEX IDX_9C720FE827289490 ON saved_lesson');
        $this->addSql('ALTER TABLE saved_lesson ADD sentences VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP sentence_id');
    }
}
