<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230719181127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE student (
                        id UUID NOT NULL, 
                        email VARCHAR(180) NOT NULL, 
                        roles JSON NOT NULL, 
                        password_hash VARCHAR(255) NOT NULL, 
                        name VARCHAR(255) NOT NULL, 
                        surname VARCHAR(255) NOT NULL, 
                        is_email_verified BOOLEAN NOT NULL,
                        PRIMARY KEY(id))'
                    );
        $this->addSql('CREATE UNIQUE INDEX uk_student_email ON student (email)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE student');
    }
}
