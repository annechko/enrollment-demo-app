<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230428124110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE school_course_date (id SERIAL, course_id UUID NOT NULL, start_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN school_course_date.start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE UNIQUE INDEX uk_school_course_date_course_start ON school_course_date (course_id, start_date)');
        $this->addSql('ALTER TABLE school_course_date ADD CONSTRAINT FK_313AD8CC591CC992 FOREIGN KEY (course_id) REFERENCES school_course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE school_course_date DROP CONSTRAINT FK_313AD8CC591CC992');
        $this->addSql('DROP TABLE school_course_date');
    }
}
