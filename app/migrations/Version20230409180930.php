<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230409180930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE admin_user RENAME COLUMN password TO password_hash');
        $this->addSql('ALTER INDEX uniq_8d93d649e7927c74 RENAME TO uk_admin_user_email');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE admin_user RENAME COLUMN password_hash TO password');
        $this->addSql('ALTER INDEX uk_admin_user_email RENAME TO uniq_8d93d649e7927c74');
    }
}
