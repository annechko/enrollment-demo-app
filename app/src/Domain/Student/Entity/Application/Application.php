<?php

declare(strict_types=1);

namespace App\Domain\Student\Entity\Application;

use App\Domain\School\Entity\Course\Course;
use App\Domain\School\Entity\Course\Intake\Intake;
use App\Domain\School\Entity\School\School;
use App\Domain\Student\Entity\Student\Student;
use App\Domain\Student\Repository\StudentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[ORM\Table(name: 'student_application')]
class Application
{
    #[ORM\Column(type: ApplicationIdType::NAME)]
    #[ORM\Id()]
    private ApplicationId $id;

    #[ORM\Column(type: Types::STRING, length: 180, nullable: false)]
    private string $passportNumber;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $passportExpiry;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $dateOfBirth;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private string $fullName;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $preferredName;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    /**
     * @phpstan-ignore property.onlyWritten
     *
     * @phpstan-ignore-next-line
     */
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private School $school;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private Course $course;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private Intake $intake;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private Student $student;

    public function __construct(
        ApplicationId $id,
        Student $student,
        School $school,
        Course $course,
        Intake $intake,
        string $passportNumber,
        \DateTimeImmutable $passportExpiry,
        \DateTimeImmutable $dateOfBirth,
        string $fullName,
        ?string $preferredName,
    ) {
        $this->id = $id;
        $this->school = $school;
        $this->course = $course;
        $this->intake = $intake;
        $this->passportNumber = $passportNumber;
        $this->passportExpiry = $passportExpiry;
        $this->dateOfBirth = $dateOfBirth;
        $this->fullName = $fullName;
        $this->preferredName = $preferredName;
        $this->createdAt = new \DateTimeImmutable();
        $this->student = $student;
    }

    public function getId(): ApplicationId
    {
        return $this->id;
    }


}
