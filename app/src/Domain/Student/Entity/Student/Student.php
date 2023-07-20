<?php

declare(strict_types=1);

namespace App\Domain\Student\Entity\Student;

use App\Domain\Student\Repository\StudentRepository;
use App\Domain\School\Common\RoleEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[ORM\Table(name: 'student')]
#[ORM\UniqueConstraint('uk_student_email', ['email'])]
class Student
{
    #[ORM\Column(type: StudentIdType::NAME)]
    #[ORM\Id()]
    private StudentId $id;

    #[ORM\Column(length: 180, unique: true, nullable: false)]
    private string $email;

    /** @var array<int, string> */
    #[ORM\Column]
    private array $roles;

    #[ORM\Column(nullable: false)]
    private string $passwordHash;

    #[ORM\Column(length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(length: 255, nullable: false)]
    private string $surname;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private $isEmailVerified = false;

    public function __construct(
        StudentId $id,
        string $email,
        string $passwordHash,
        string $name,
        string $surname,
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->name = $name;
        $this->surname = $surname;
        $this->roles = [
            RoleEnum::STUDENT_USER->value,
        ];
    }

    public function getId(): StudentId
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return array<int, string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return $this->passwordHash;
    }

    public function upgradePasswordHash(string $password): self
    {
        $this->passwordHash = $password;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }
}
