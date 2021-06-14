<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210614124403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE board (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, visibility TINYINT(1) NOT NULL, score INT NOT NULL, url_title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE board_user (board_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_57058F6AE7EC5785 (board_id), INDEX IDX_57058F6AA76ED395 (user_id), PRIMARY KEY(board_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, board_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, score INT NOT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_5A8A6C8DF675F31B (author_id), INDEX IDX_5A8A6C8DE7EC5785 (board_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_board (user_id INT NOT NULL, board_id INT NOT NULL, INDEX IDX_BA94D01FA76ED395 (user_id), INDEX IDX_BA94D01FE7EC5785 (board_id), PRIMARY KEY(user_id, board_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE board_user ADD CONSTRAINT FK_57058F6AE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE board_user ADD CONSTRAINT FK_57058F6AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id)');
        $this->addSql('ALTER TABLE user_board ADD CONSTRAINT FK_BA94D01FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_board ADD CONSTRAINT FK_BA94D01FE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE board_user DROP FOREIGN KEY FK_57058F6AE7EC5785');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DE7EC5785');
        $this->addSql('ALTER TABLE user_board DROP FOREIGN KEY FK_BA94D01FE7EC5785');
        $this->addSql('ALTER TABLE board_user DROP FOREIGN KEY FK_57058F6AA76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF675F31B');
        $this->addSql('ALTER TABLE user_board DROP FOREIGN KEY FK_BA94D01FA76ED395');
        $this->addSql('DROP TABLE board');
        $this->addSql('DROP TABLE board_user');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_board');
    }
}
