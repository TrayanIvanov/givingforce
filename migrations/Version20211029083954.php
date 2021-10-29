<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211029083954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create applications table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE applications 
            (
                application_id INT AUTO_INCREMENT NOT NULL,
                user_id INT NOT NULL,
                charity_id INT NOT NULL,
                stage VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL,
                PRIMARY KEY(application_id),
                CONSTRAINT `fk_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
                CONSTRAINT `fk_charities` FOREIGN KEY (`charity_id`) REFERENCES `charities` (`charity_id`)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE applications');
    }
}
