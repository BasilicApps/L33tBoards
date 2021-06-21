<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210621195056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE board_user_like (board_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_42D7FEE3E7EC5785 (board_id), INDEX IDX_42D7FEE3A76ED395 (user_id), PRIMARY KEY(board_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE board_user_dislike (board_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5DBDE981E7EC5785 (board_id), INDEX IDX_5DBDE981A76ED395 (user_id), PRIMARY KEY(board_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE board_user_like ADD CONSTRAINT FK_42D7FEE3E7EC5785 FOREIGN KEY (board_id) REFERENCES board (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE board_user_like ADD CONSTRAINT FK_42D7FEE3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE board_user_dislike ADD CONSTRAINT FK_5DBDE981E7EC5785 FOREIGN KEY (board_id) REFERENCES board (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE board_user_dislike ADD CONSTRAINT FK_5DBDE981A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE board_user_like');
        $this->addSql('DROP TABLE board_user_dislike');
    }
}
