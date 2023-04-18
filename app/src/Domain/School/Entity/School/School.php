<?php

declare(strict_types=1);

namespace App\Domain\School\Entity\School;

use App\Domain\School\Common\RoleEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

#[ORM\Entity]
#[ORM\Table(name: 'school_school')]
class School
{
    public const STATUS_NEW = 'NEW';
    public const STATUS_ACTIVE = 'ACTIVE';

    #[ORM\Column(type: SchoolIdType::NAME)]
    #[ORM\Id]
    private SchoolId $id;

    #[ORM\Embedded(class: Name::class, columnPrefix: false)]
    private Name $name;

    #[ORM\OneToOne(targetEntity: StaffMember::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'admin_id', referencedColumnName: 'id', nullable: false)]
    private StaffMember $admin;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private string $status;

    private function __construct(
        SchoolId $id,
        Name $name,
        StaffMemberId $adminId,
        StaffMemberName $adminName,
        Email $adminEmail,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->status = self::STATUS_NEW;
        $this->admin = new StaffMember($adminId, $adminName, $adminEmail);
        $this->admin->changeRole(RoleEnum::SCHOOL_ADMIN);
    }

    public static function register(
        SchoolId $id,
        Name $schoolName,
        StaffMemberId $adminId,
        StaffMemberName $adminName,
        Email $adminEmail,
    ): self {
        return new self($id, $schoolName, $adminId, $adminName, $adminEmail);
    }

    public function confirmRegister(
        InvitationToken $token
    ): void {
        if ($this->status !== self::STATUS_NEW) {
            throw new DomainException('School can not be confirmed.');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->admin->invite($token);
    }

    public function getAdmin(): StaffMember
    {
        return $this->admin;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getId(): SchoolId
    {
        return $this->id;
    }
}
