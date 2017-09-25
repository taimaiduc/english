<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170924081340 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE saved_sentence (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, lesson_id INT NOT NULL, sentence_id INT NOT NULL, INDEX IDX_F38749FDA76ED395 (user_id), INDEX IDX_F38749FDCDF80196 (lesson_id), INDEX IDX_F38749FD27289490 (sentence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE saved_sentence ADD CONSTRAINT FK_F38749FDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_sentence ADD CONSTRAINT FK_F38749FDCDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
        $this->addSql('ALTER TABLE saved_sentence ADD CONSTRAINT FK_F38749FD27289490 FOREIGN KEY (sentence_id) REFERENCES sentence (id)');
        $this->addSql('DROP TABLE saved_lesson');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE saved_lesson (id INT AUTO_INCREMENT NOT NULL, sentence_id INT NOT NULL, user_id INT NOT NULL, lesson_id INT NOT NULL, INDEX IDX_9C720FE8A76ED395 (user_id), INDEX IDX_9C720FE8CDF80196 (lesson_id), INDEX IDX_9C720FE827289490 (sentence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE saved_lesson ADD CONSTRAINT FK_9C720FE827289490 FOREIGN KEY (sentence_id) REFERENCES sentence (id)');
        $this->addSql('ALTER TABLE saved_lesson ADD CONSTRAINT FK_9C720FE8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_lesson ADD CONSTRAINT FK_9C720FE8CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
        $this->addSql('DROP TABLE saved_sentence');
    }
}
