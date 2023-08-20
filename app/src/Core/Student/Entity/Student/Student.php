<?php

declare(strict_types=1);

namespace App\Core\Student\Entity\Student;

use App\Core\School\Common\RoleEnum;
use App\Core\Student\Repository\StudentRepository;
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

    private function __construct(
        StudentId $id,
        string $email,
        string $name,
        string $surname,
        string $passwordHash,
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->passwordHash = $passwordHash;
        $this->roles = [
            RoleEnum::STUDENT_USER->value,
        ];
    }

    public static function register(
        StudentId $id,
        string $email,
        string $name,
        string $surname,
        string $passwordHash
    ): self {
        return new self(
            $id,
            $email,
            $name,
            $surname,
            $passwordHash
        );
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

    public function getPasswordHash(): string
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

    public function verifyEmail(): self
    {
        $this->isEmailVerified = true;

        return $this;
    }

    public function isEmailVerified(): bool
    {
        return $this->isEmailVerified;
    }
}
