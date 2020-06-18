<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617145750 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tasks (task_id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX fk_tasks_project_idx (project_id), PRIMARY KEY(task_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (user_id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects (project_id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX fk_projects_user_idx (user_id), PRIMARY KEY(project_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597166D1F9C FOREIGN KEY (project_id) REFERENCES projects (project_id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4A76ED395 FOREIGN KEY (user_id) REFERENCES users (user_id)');

        $this->addSql('INSERT INTO users (name, password) VALUES("iphonedroid", "$2y$13$fXBB0E3NjQwiTNGEs4h9vu6/Tcqs/CVOAWcaL2c1.sV.AOXMv2Tnq");');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4A76ED395');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597166D1F9C');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE projects');
    }
}
