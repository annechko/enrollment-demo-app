<?php

declare(strict_types=1);

namespace App\Core\School\Entity\Campus;

use App\Core\School\Entity\School\SchoolId;
use App\Core\School\Entity\School\SchoolIdType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'school_campus')]
#[ORM\UniqueConstraint(name: 'uk_school_campus_name', columns: ['name'])]
class Campus
{
    #[ORM\Column(type: CampusIdType::NAME)]
    #[ORM\Id()]
    private CampusId $id;

    #[ORM\Column(type: SchoolIdType::NAME, nullable: false)]
    private SchoolId $schoolId;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private string $name;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $address;

    public function __construct(
        SchoolId $schoolId,
        CampusId $id,
        string $name,
        ?string $address = null,
    ) {
        $this->schoolId = $schoolId;
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
    }

    public function getId(): CampusId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function edit(
        string $name,
        ?string $address = null,
    ): self {
        $this->name = $name;
        $this->address = $address;

        return $this;
    }
}
