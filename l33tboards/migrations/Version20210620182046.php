<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210620182046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_board_vote (id INT AUTO_INCREMENT NOT NULL, liked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_board_vote_user (user_board_vote_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9A00B7DCD44C5FA3 (user_board_vote_id), INDEX IDX_9A00B7DCA76ED395 (user_id), PRIMARY KEY(user_board_vote_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_board_vote_board (user_board_vote_id INT NOT NULL, board_id INT NOT NULL, INDEX IDX_D824DFEDD44C5FA3 (user_board_vote_id), INDEX IDX_D824DFEDE7EC5785 (board_id), PRIMARY KEY(user_board_vote_id, board_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_board_vote_user ADD CONSTRAINT FK_9A00B7DCD44C5FA3 FOREIGN KEY (user_board_vote_id) REFERENCES user_board_vote (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_board_vote_user ADD CONSTRAINT FK_9A00B7DCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_board_vote_board ADD CONSTRAINT FK_D824DFEDD44C5FA3 FOREIGN KEY (user_board_vote_id) REFERENCES user_board_vote (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_board_vote_board ADD CONSTRAINT FK_D824DFEDE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_board_vote_user DROP FOREIGN KEY FK_9A00B7DCD44C5FA3');
        $this->addSql('ALTER TABLE user_board_vote_board DROP FOREIGN KEY FK_D824DFEDD44C5FA3');
        $this->addSql('DROP TABLE user_board_vote');
        $this->addSql('DROP TABLE user_board_vote_user');
        $this->addSql('DROP TABLE user_board_vote_board');
    }
}
