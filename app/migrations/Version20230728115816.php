<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230728115816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE school_school ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL'
        );
        $this->addSql('ALTER TABLE school_school ADD updated_by UUID DEFAULT NULL');
        $this->addSql(
            'COMMENT ON COLUMN school_school.updated_at IS \'(DC2Type:datetime_immutable)\''
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE school_school DROP updated_at');
        $this->addSql('ALTER TABLE school_school DROP updated_by');
    }
}
