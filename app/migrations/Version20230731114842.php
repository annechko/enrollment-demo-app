<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230731114842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX uk_school_course_school_name ON school_course (school_id, name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX uk_school_course_school_name');
    }
}
