<?php

declare(strict_types=1);

namespace App\Domain\School\Entity\Course;

use App\Domain\School\Entity\Campus\Campus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private \DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, Campus>
     */
    #[ORM\JoinTable(name: 'school_course_to_campus')]
    #[ORM\JoinColumn(name: 'course_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'campus_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Campus::class)]
    private Collection $campuses;

    /**
     * @var Collection<int, CourseDate>
     */
    #[ORM\OneToMany(mappedBy: 'course', targetEntity: CourseDate::class,
        cascade: ['persist'], orphanRemoval: true)]
    private Collection $dates;

    /**
     * @param array<Campus> $campuses
     * @param array<\DateTimeImmutable> $dates
     */
    public function __construct(
        CourseId $id,
        string $name,
        ?string $description = null,
        array $campuses = [],
        array $dates = [],
        ?\DateTimeImmutable $createdAt = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->setCampuses($campuses);
        $this->dates = new ArrayCollection();
        foreach ($dates as $startDate) {
            $this->dates->add(new CourseDate($this, $startDate));
        }
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

    /**
     * @param array<Campus> $campuses
     * @param array<\DateTimeImmutable> $startDates
     */
    public function edit(
        string $name,
        ?string $description = null,
        array $campuses = [],
        array $startDates = [],
    ): self {
        $this->name = $name;
        $this->description = $description;

        $updatedCampusesIds = array_map(
            fn (Campus $c) => $c->getId()->getValue(),
            $campuses
        );

        // remove
        foreach ($this->campuses as $campus) {
            if (!in_array($campus->getId()->getValue(), $updatedCampusesIds, true)) {
                $this->campuses->removeElement($campus);
            }
        }
        // add new
        foreach ($campuses as $campus) {
            if (!$this->campuses->contains($campus)) {
                $this->campuses->add($campus);
            }
        }

        $updatedStartDatesKeys = array_map(
            fn (\DateTimeImmutable $d) => $d->format('Ymd'),
            $startDates
        );
        $currentStartDatesKeys = array_map(
            fn (CourseDate $d) => $d->getStartDate()->format('Ymd'),
            $this->dates->toArray()
        );
        // remove
        foreach ($this->dates as $date) {
            if (!in_array($date->getStartDate()->format('Ymd'), $updatedStartDatesKeys, true)) {
                $this->dates->removeElement($date);
            }
        }
        // add new
        foreach ($startDates as $date) {
            if (!in_array($date->format('Ymd'), $currentStartDatesKeys, true)) {
                $this->dates->add(new CourseDate($this, $date));
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Campus>
     */
    public function getCampuses(): Collection
    {
        return $this->campuses;
    }

    private function setCampuses(array $campuses): self
    {
        $this->campuses = new ArrayCollection();
        foreach ($campuses as $campus) {
            $this->campuses->add($campus);
        }

        return $this;
    }

    /**
     * @return Collection<int, CourseDate>
     */
    public function getDates(): Collection
    {
        return $this->dates;
    }
}
