<?php

declare(strict_types=1);

namespace App\Domain\School\Entity\Course;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'school_course')]
class Course
{
    #[ORM\Column(type: CourseIdType::NAME)]
    #[ORM\Id()]
    private CourseId $id;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private string $name;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private DateTimeImmutable $createdAt;

    public function __construct(
        CourseId $id,
        string $name,
        ?string $description = null,
        ?DateTimeImmutable $createdAt = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
    }

    public function getId(): CourseId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function edit(
        string $name,
        ?string $description = null,
    ): self {
        $this->name = $name;
        $this->description = $description;
        return $this;
    }
}
