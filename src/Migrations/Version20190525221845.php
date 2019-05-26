<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190525221845 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('CREATE TABLE task_application_user (task_application_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EA86D6C7222A7C62 (task_application_id), INDEX IDX_EA86D6C7A76ED395 (user_id), PRIMARY KEY(task_application_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task_application_user ADD CONSTRAINT FK_EA86D6C7222A7C62 FOREIGN KEY (task_application_id) REFERENCES task_application (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_application_user ADD CONSTRAINT FK_EA86D6C7A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE users_taskApplications');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_DADD4A251E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users_taskApplications (user_id INT NOT NULL, taskApplication_id INT NOT NULL, INDEX IDX_E751AD611920FF6A (taskApplication_id), INDEX IDX_E751AD61A76ED395 (user_id), PRIMARY KEY(user_id, taskApplication_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE users_taskApplications ADD CONSTRAINT FK_E751AD611920FF6A FOREIGN KEY (taskApplication_id) REFERENCES task_application (id)');
        $this->addSql('ALTER TABLE users_taskApplications ADD CONSTRAINT FK_E751AD61A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('DROP TABLE task_application_user');
    }
}
