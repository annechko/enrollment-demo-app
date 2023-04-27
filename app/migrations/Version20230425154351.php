<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230425154351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE school_course_to_campus (campus_id UUID NOT NULL, course_id UUID NOT NULL, PRIMARY KEY(campus_id, course_id))');
        $this->addSql('CREATE INDEX IDX_E6846E3BAF5D55E1 ON school_course_to_campus (campus_id)');
        $this->addSql('CREATE INDEX IDX_E6846E3B591CC992 ON school_course_to_campus (course_id)');
        $this->addSql('ALTER TABLE school_course_to_campus ADD CONSTRAINT FK_E6846E3BAF5D55E1 FOREIGN KEY (campus_id) REFERENCES school_campus (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE school_course_to_campus ADD CONSTRAINT FK_E6846E3B591CC992 FOREIGN KEY (course_id) REFERENCES school_course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE school_course_to_campus');
    }
}
