<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230727143319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE school_course ADD school_id UUID NOT NULL');
        $this->addSql('CREATE INDEX IDX_EB226C75C32A47EE ON school_course (school_id)');

        $this->addSql('ALTER TABLE school_staff_member ADD school_id UUID NOT NULL');
        $this->addSql('CREATE INDEX IDX_BEABFD7DC32A47EE ON school_staff_member (school_id)');

        $this->addSql('ALTER TABLE school_campus ADD school_id UUID NOT NULL');
        $this->addSql('CREATE INDEX IDX_60B56BDDC32A47EE ON school_campus (school_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE school_course DROP school_id');
        $this->addSql('ALTER TABLE school_campus DROP school_id');
        $this->addSql('ALTER TABLE school_staff_member DROP school_id');
    }
}
