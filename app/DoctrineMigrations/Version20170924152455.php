<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170924152455 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lesson ADD point INT NOT NULL');
        $this->addSql('ALTER TABLE saved_sentence DROP FOREIGN KEY FK_F38749FDF823486B');
        $this->addSql('ALTER TABLE saved_sentence ADD CONSTRAINT FK_F38749FDF823486B FOREIGN KEY (saved_lesson_id) REFERENCES saved_lesson (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sentence ADD point INT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lesson DROP point');
        $this->addSql('ALTER TABLE saved_sentence DROP FOREIGN KEY FK_F38749FDF823486B');
        $this->addSql('ALTER TABLE saved_sentence ADD CONSTRAINT FK_F38749FDF823486B FOREIGN KEY (saved_lesson_id) REFERENCES saved_lesson (id)');
        $this->addSql('ALTER TABLE sentence DROP point');
    }
}
