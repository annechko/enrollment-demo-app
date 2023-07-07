<?php

declare(strict_types=1);

namespace App\Domain\School\Entity\Course\Intake;

use App\Domain\School\Entity\Campus\Campus;
use App\Domain\School\Entity\Course\Course;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity()]
#[ORM\Table(name: 'school_course_intake')]
class Intake
{
    #[ORM\Column(type: IntakeIdType::NAME)]
    #[ORM\Id()]
    private IntakeId $id;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $name;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $classSize;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $startDate;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $endDate;

    #[ORM\ManyToOne(targetEntity: Campus::class)]
    private ?Campus $campus;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'intakes')]
    #[ORM\JoinColumn(nullable: false)]
    /**
     * @phpstan-ignore property.onlyWritten
     * @phpstan-ignore-next-line
     */
    private Course $course;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    /**
     * @phpstan-ignore property.onlyWritten
     * @phpstan-ignore-next-line
     */
    private \DateTimeImmutable $createdAt;

    public function __construct(
        Course $course,
        IntakeId $id,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        ?string $name,
        ?int $classSize,
        ?Campus $campus,
        ?\DateTimeImmutable $createdAt = null,
    ) {
        Assert::nullOrGreaterThanEq($classSize, 0);
        Assert::greaterThanEq(
            $endDate,
            $startDate,
            'Start date should be greater than or equal to end date.'
        );

        $this->course = $course;
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->name = $name;
        $this->classSize = $classSize;
        $this->campus = $campus;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
    }

    public function getId(): IntakeId
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getClassSize(): ?int
    {
        return $this->classSize;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function edit(
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        ?string $name,
        ?int $classSize,
        ?Campus $campus,
    ): self {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->name = $name;
        $this->classSize = $classSize;
        $this->campus = $campus;
        return $this;
    }
}
