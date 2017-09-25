<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170924085539 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE saved_lesson (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, lesson_id INT NOT NULL, INDEX IDX_9C720FE8A76ED395 (user_id), INDEX IDX_9C720FE8CDF80196 (lesson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE saved_lesson ADD CONSTRAINT FK_9C720FE8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_lesson ADD CONSTRAINT FK_9C720FE8CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
        $this->addSql('ALTER TABLE saved_sentence DROP FOREIGN KEY FK_F38749FDA76ED395');
        $this->addSql('ALTER TABLE saved_sentence DROP FOREIGN KEY FK_F38749FDCDF80196');
        $this->addSql('DROP INDEX IDX_F38749FDA76ED395 ON saved_sentence');
        $this->addSql('DROP INDEX IDX_F38749FDCDF80196 ON saved_sentence');
        $this->addSql('ALTER TABLE saved_sentence ADD saved_lesson_id INT NOT NULL, DROP user_id, DROP lesson_id');
        $this->addSql('ALTER TABLE saved_sentence ADD CONSTRAINT FK_F38749FDF823486B FOREIGN KEY (saved_lesson_id) REFERENCES saved_lesson (id)');
        $this->addSql('CREATE INDEX IDX_F38749FDF823486B ON saved_sentence (saved_lesson_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE saved_sentence DROP FOREIGN KEY FK_F38749FDF823486B');
        $this->addSql('DROP TABLE saved_lesson');
        $this->addSql('DROP INDEX IDX_F38749FDF823486B ON saved_sentence');
        $this->addSql('ALTER TABLE saved_sentence ADD lesson_id INT NOT NULL, CHANGE saved_lesson_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE saved_sentence ADD CONSTRAINT FK_F38749FDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_sentence ADD CONSTRAINT FK_F38749FDCDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
        $this->addSql('CREATE INDEX IDX_F38749FDA76ED395 ON saved_sentence (user_id)');
        $this->addSql('CREATE INDEX IDX_F38749FDCDF80196 ON saved_sentence (lesson_id)');
    }
}
