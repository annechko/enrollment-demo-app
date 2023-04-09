<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230409121705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" RENAME TO admin_user;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE admin_user RENAME TO "user";');
    }
}
