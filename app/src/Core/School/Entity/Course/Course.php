<?php

declare(strict_types=1);

namespace App\Core\School\Entity\Course;

use App\Core\Common\NotFoundException;
use App\Core\School\Entity\Campus\Campus;
use App\Core\School\Entity\Course\Intake\Intake;
use App\Core\School\Entity\Course\Intake\IntakeId;
use App\Core\School\Entity\School\SchoolId;
use App\Core\School\Entity\School\SchoolIdType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'school_course')]
#[ORM\UniqueConstraint('uk_school_course_school_name', ['school_id', 'name'])]
class Course
{
    #[ORM\Column(type: CourseIdType::NAME)]
    #[ORM\Id()]
    private CourseId $id;

    #[ORM\Column(type: SchoolIdType::NAME)]
    private SchoolId $schoolId;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private string $name;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $description;

    /**
     * @var Collection<string, Intake>
     */
    #[ORM\OneToMany(mappedBy: 'course', targetEntity: Intake::class,
        cascade: ['persist'],
        orphanRemoval: true,
        indexBy: 'id')]
    private Collection $intakes;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    /**
     * @phpstan-ignore property.onlyWritten
     *
     * @phpstan-ignore-next-line
     */
    private \DateTimeImmutable $createdAt;

    public function __construct(
        SchoolId $schoolId,
        CourseId $id,
        string $name,
        string $description = null,
        \DateTimeImmutable $createdAt = null,
    ) {
        $this->schoolId = $schoolId;
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->intakes = new ArrayCollection();
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
        string $description = null,
    ): self {
        $this->name = $name;
        $this->description = $description;

        return $this;
    }

    public function addIntake(
        IntakeId $id,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        string $name = null,
        int $classSize = null,
        Campus $campus = null,
        \DateTimeImmutable $createdAt = null,
    ): Intake {
        $intake = new Intake(
            $this,
            $id,
            $startDate,
            $endDate,
            $name,
            $classSize,
            $campus,
            $createdAt,
        );
        $this->intakes->add($intake);

        return $intake;
    }

    public function removeIntake(IntakeId $id): self
    {
        $this->intakes->remove($id->getValue());

        return $this;
    }

    public function editIntake(
        IntakeId $id,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        ?string $name,
        ?int $classSize,
        ?Campus $campus,
    ): self {
        $intake = $this->getIntake($id);

        $intake->edit(
            $startDate,
            $endDate,
            $name,
            $classSize,
            $campus,
        );

        return $this;
    }

    /**
     * @return array<Intake>
     */
    public function getIntakes(): array
    {
        return $this->intakes->toArray();
    }

    public function getIntake(IntakeId $id): Intake
    {
        $intake = $this->intakes->get($id->getValue());
        if ($intake === null) {
            throw new NotFoundException('Intake not found.');
        }

        return $intake;
    }
}
