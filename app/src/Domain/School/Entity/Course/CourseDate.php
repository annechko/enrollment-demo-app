<?php

declare(strict_types=1);

namespace App\Domain\School\Entity\Course;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'school_course_date')]
#[ORM\UniqueConstraint(name: 'uk_school_course_date_course_id', columns: ['course_id', 'start_date'])]
class CourseDate
{
    #[ORM\Id()]
    #[ORM\Column()]
    #[ORM\GeneratedValue()]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'dates')]
    private Course $course;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $startDate;

    public function __construct(
        Course $course,
        \DateTimeImmutable $startDate
    ) {
        $this->course = $course;
        $this->startDate = $startDate;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }
}
