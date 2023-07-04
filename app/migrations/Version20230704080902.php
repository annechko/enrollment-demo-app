<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230704080902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE school_course_intake (
                            id UUID NOT NULL, 
                            course_id UUID NOT NULL,
                            campus_id UUID DEFAULT NULL, 
                            name VARCHAR(255) DEFAULT NULL,
                             class_size INT DEFAULT NULL, 
                             start_date DATE NOT NULL, 
                             end_date DATE NOT NULL, 
                             created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                             PRIMARY KEY(id)
                          )'
        );
        $this->addSql('CREATE INDEX IDX_2C88EA09AF5D55E1 ON school_course_intake (campus_id)');
        $this->addSql('CREATE INDEX IDX_2C88EA09591CC992 ON school_course_intake (course_id)');

        $this->addSql(
            'COMMENT ON COLUMN school_course_intake.start_date IS \'(DC2Type:date_immutable)\''
        );
        $this->addSql(
            'COMMENT ON COLUMN school_course_intake.end_date IS \'(DC2Type:date_immutable)\''
        );
        $this->addSql(
            'COMMENT ON COLUMN school_course_intake.created_at IS \'(DC2Type:datetime_immutable)\''
        );
        $this->addSql(
            'ALTER TABLE school_course_intake ADD CONSTRAINT FK_2C88EA09AF5D55E1 FOREIGN KEY (campus_id) REFERENCES school_campus (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE school_course_intake ADD CONSTRAINT FK_2C88EA09591CC992 FOREIGN KEY (course_id) REFERENCES school_course (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE school_course_to_campus DROP CONSTRAINT fk_e6846e3baf5d55e1');
        $this->addSql('ALTER TABLE school_course_to_campus DROP CONSTRAINT fk_e6846e3b591cc992');
        $this->addSql('ALTER TABLE school_course_date DROP CONSTRAINT fk_313ad8cc591cc992');
        $this->addSql('DROP TABLE school_course_to_campus');
        $this->addSql('DROP TABLE school_course_date');
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE school_course_to_campus (campus_id UUID NOT NULL, course_id UUID NOT NULL, PRIMARY KEY(campus_id, course_id))'
        );
        $this->addSql('CREATE INDEX idx_e6846e3b591cc992 ON school_course_to_campus (course_id)');
        $this->addSql('CREATE INDEX idx_e6846e3baf5d55e1 ON school_course_to_campus (campus_id)');
        $this->addSql(
            'CREATE TABLE school_course_date (id SERIAL NOT NULL, course_id UUID NOT NULL, start_date DATE NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql(
            'CREATE UNIQUE INDEX uk_school_course_date_course_start ON school_course_date (course_id, start_date)'
        );
        $this->addSql('CREATE INDEX IDX_313AD8CC591CC992 ON school_course_date (course_id)');
        $this->addSql(
            'COMMENT ON COLUMN school_course_date.start_date IS \'(DC2Type:date_immutable)\''
        );
        $this->addSql(
            'ALTER TABLE school_course_to_campus ADD CONSTRAINT fk_e6846e3baf5d55e1 FOREIGN KEY (campus_id) REFERENCES school_campus (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE school_course_to_campus ADD CONSTRAINT fk_e6846e3b591cc992 FOREIGN KEY (course_id) REFERENCES school_course (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE school_course_date ADD CONSTRAINT fk_313ad8cc591cc992 FOREIGN KEY (course_id) REFERENCES school_course (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE school_course_intake DROP CONSTRAINT FK_2C88EA09AF5D55E1');
        $this->addSql('ALTER TABLE school_course_intake DROP CONSTRAINT FK_2C88EA09591CC992');
        $this->addSql('DROP TABLE school_course_intake');
        $this->addSql('DROP INDEX IDX_2C88EA09AF5D55E1');
        $this->addSql('DROP INDEX IDX_2C88EA09591CC992');
    }
}
