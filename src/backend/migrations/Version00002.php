<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version00002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create main wallet table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE currency (
                id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL
                , code VARCHAR(3) NOT NULL 
                , PRIMARY KEY(id) 
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE currency_rate (
                id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL
                , currency_from_id BIGINT UNSIGNED NOT NULL
                , currency_to_id BIGINT UNSIGNED NOT NULL
                , rate DOUBLE(15,4) NOT NULL 
                , PRIMARY KEY (id) 
                , UNIQUE INDEX currency_rate_from_to_idx (currency_from_id, currency_to_id) 
                , CONSTRAINT currency_rate_from_idx_fk FOREIGN KEY (currency_from_id) REFERENCES currency(id)
                , CONSTRAINT currency_rate_to_idx_fk FOREIGN KEY (currency_to_id) REFERENCES currency(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE wallet (
                id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL
                , user_id BIGINT UNSIGNED NOT NULL
                , currency_id BIGINT UNSIGNED NOT NULL
                , balance DECIMAL(15,4) NOT NULL DEFAULT 0.0
                , PRIMARY KEY(id)
                , CONSTRAINT wallet_user_id_idx_fk FOREIGN KEY (user_id) REFERENCES user(id)
                , CONSTRAINT wallet_currency_id_idx_fk FOREIGN KEY (currency_id) REFERENCES currency(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE wallet_history (
                id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL
                , wallet_id BIGINT UNSIGNED NOT NULL
                , currency_id BIGINT UNSIGNED NOT NULL
                , type SMALLINT UNSIGNED NOT NULL
                , reason SMALLINT UNSIGNED NOT NULL
                , value DECIMAL(15,4) UNSIGNED NOT NULL
                , rate DECIMAL(15,4) UNSIGNED NOT NULL
                , created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                , PRIMARY KEY(id) 
                , CONSTRAINT wallet_history_wallet_id_idx_fk FOREIGN KEY (wallet_id) REFERENCES wallet(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE wallet_history');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP TABLE currency_rate');
        $this->addSql('DROP TABLE currency');
    }
}
