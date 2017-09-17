<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170916042923 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, total_lessons SMALLINT NOT NULL, position SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE done_lesson (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, lesson_id INT NOT NULL, count SMALLINT NOT NULL, INDEX IDX_3BE7B34CA76ED395 (user_id), INDEX IDX_3BE7B34CCDF80196 (lesson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, position SMALLINT NOT NULL, INDEX IDX_F87474F312469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE progress (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date DATE NOT NULL, point INT NOT NULL, INDEX IDX_2201F246A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saved_lesson (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, lesson_id INT NOT NULL, sentences VARCHAR(255) NOT NULL, INDEX IDX_9C720FE8A76ED395 (user_id), INDEX IDX_9C720FE8CDF80196 (lesson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sentence (id INT AUTO_INCREMENT NOT NULL, lesson_id INT NOT NULL, content VARCHAR(255) NOT NULL, position SMALLINT NOT NULL, INDEX IDX_9D664ED5CDF80196 (lesson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', total_point INT NOT NULL, created_at DATE NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE done_lesson ADD CONSTRAINT FK_3BE7B34CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE done_lesson ADD CONSTRAINT FK_3BE7B34CCDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE progress ADD CONSTRAINT FK_2201F246A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_lesson ADD CONSTRAINT FK_9C720FE8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_lesson ADD CONSTRAINT FK_9C720FE8CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
        $this->addSql('ALTER TABLE sentence ADD CONSTRAINT FK_9D664ED5CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F312469DE2');
        $this->addSql('ALTER TABLE done_lesson DROP FOREIGN KEY FK_3BE7B34CCDF80196');
        $this->addSql('ALTER TABLE saved_lesson DROP FOREIGN KEY FK_9C720FE8CDF80196');
        $this->addSql('ALTER TABLE sentence DROP FOREIGN KEY FK_9D664ED5CDF80196');
        $this->addSql('ALTER TABLE done_lesson DROP FOREIGN KEY FK_3BE7B34CA76ED395');
        $this->addSql('ALTER TABLE progress DROP FOREIGN KEY FK_2201F246A76ED395');
        $this->addSql('ALTER TABLE saved_lesson DROP FOREIGN KEY FK_9C720FE8A76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE done_lesson');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE progress');
        $this->addSql('DROP TABLE saved_lesson');
        $this->addSql('DROP TABLE sentence');
        $this->addSql('DROP TABLE user');
    }
}
