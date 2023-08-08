<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230808135454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE student_application (
                                id UUID NOT NULL, 
                                student_id UUID NOT NULL,
                                school_id UUID NOT NULL,
                                course_id UUID NOT NULL, 
                                intake_id UUID NOT NULL, 
                                passport_number VARCHAR(180) NOT NULL, 
                                passport_expiry TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                                date_of_birth TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                                full_name VARCHAR(255) NOT NULL, 
                                preferred_name VARCHAR(255) DEFAULT NULL, 
                                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                                PRIMARY KEY(id)
                     )');
        $this->addSql('CREATE INDEX IDX_73BBD0C2C32A47EE ON student_application (school_id)');
        $this->addSql('CREATE INDEX IDX_73BBD0C2591CC992 ON student_application (course_id)');
        $this->addSql('CREATE INDEX IDX_73BBD0C2733DE450 ON student_application (intake_id)');
        $this->addSql('CREATE INDEX IDX_73BBD0C2CB944F1A ON student_application (student_id)');
        $this->addSql('COMMENT ON COLUMN student_application.passport_expiry IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN student_application.date_of_birth IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN student_application.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE student_application ADD CONSTRAINT FK_73BBD0C2C32A47EE FOREIGN KEY (school_id) REFERENCES school_school (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_application ADD CONSTRAINT FK_73BBD0C2591CC992 FOREIGN KEY (course_id) REFERENCES school_course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_application ADD CONSTRAINT FK_73BBD0C2733DE450 FOREIGN KEY (intake_id) REFERENCES school_course_intake (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_application ADD CONSTRAINT FK_73BBD0C2CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE student_application DROP CONSTRAINT FK_73BBD0C2C32A47EE');
        $this->addSql('ALTER TABLE student_application DROP CONSTRAINT FK_73BBD0C2591CC992');
        $this->addSql('ALTER TABLE student_application DROP CONSTRAINT FK_73BBD0C2733DE450');
        $this->addSql('ALTER TABLE student_application DROP CONSTRAINT FK_73BBD0C2CB944F1A');
        $this->addSql('DROP TABLE student_application');
    }
}
