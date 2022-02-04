<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version00001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create main user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE user (
                id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL
                , name VARCHAR(255) NOT NULL
                , login VARCHAR(255) NOT NULL
                , password VARCHAR(255) NOT NULL
                , role SMALLINT UNSIGNED DEFAULT 0
                , PRIMARY KEY(id), UNIQUE INDEX user_login_idx(login) 
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user');
    }
}
