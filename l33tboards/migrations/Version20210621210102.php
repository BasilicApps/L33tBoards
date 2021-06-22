<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210621210102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_user_like (post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_778C728D4B89032C (post_id), INDEX IDX_778C728DA76ED395 (user_id), PRIMARY KEY(post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_user_dislike (post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D0B8F734B89032C (post_id), INDEX IDX_D0B8F73A76ED395 (user_id), PRIMARY KEY(post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_user_like ADD CONSTRAINT FK_778C728D4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_user_like ADD CONSTRAINT FK_778C728DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_user_dislike ADD CONSTRAINT FK_D0B8F734B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_user_dislike ADD CONSTRAINT FK_D0B8F73A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE board_user_like RENAME INDEX idx_42d7fee3e7ec5785 TO IDX_64CFF051E7EC5785');
        $this->addSql('ALTER TABLE board_user_like RENAME INDEX idx_42d7fee3a76ed395 TO IDX_64CFF051A76ED395');
        $this->addSql('ALTER TABLE board_user_dislike RENAME INDEX idx_5dbde981e7ec5785 TO IDX_6FF8A9D7E7EC5785');
        $this->addSql('ALTER TABLE board_user_dislike RENAME INDEX idx_5dbde981a76ed395 TO IDX_6FF8A9D7A76ED395');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE post_user_like');
        $this->addSql('DROP TABLE post_user_dislike');
        $this->addSql('ALTER TABLE board_user_dislike RENAME INDEX idx_6ff8a9d7e7ec5785 TO IDX_5DBDE981E7EC5785');
        $this->addSql('ALTER TABLE board_user_dislike RENAME INDEX idx_6ff8a9d7a76ed395 TO IDX_5DBDE981A76ED395');
        $this->addSql('ALTER TABLE board_user_like RENAME INDEX idx_64cff051e7ec5785 TO IDX_42D7FEE3E7EC5785');
        $this->addSql('ALTER TABLE board_user_like RENAME INDEX idx_64cff051a76ed395 TO IDX_42D7FEE3A76ED395');
    }
}
