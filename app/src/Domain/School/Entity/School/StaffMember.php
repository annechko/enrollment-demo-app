<?php

declare(strict_types=1);

namespace App\Domain\School\Entity\School;

use App\Domain\School\Common\RoleEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'school_staff_member')]
class StaffMember
{
    private const STATUS_CREATED = 'created';
    private const STATUS_INVITED = 'invited';
    private const STATUS_ACTIVE = 'active';

    #[ORM\Column(type: StaffMemberIdType::NAME)]
    #[ORM\Id()]
    private StaffMemberId $id;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    private string $surname;

    #[ORM\Embedded(class: Email::class, columnPrefix: false)]
    private Email $email;

    #[ORM\Column(type: Types::STRING)]
    private string $status;

    #[ORM\Embedded(class: InvitationToken::class)]
    private ?InvitationToken $invitationToken = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $passwordHash;

    /** @var array<int, string> */
    #[ORM\Column(options: ['default' => '[]'])]
    private array $roles = [];

    #[ORM\Column(type: SchoolIdType::NAME)]
    private SchoolId $schoolId;

    public function __construct(
        SchoolId $schoolId,
        StaffMemberId $id,
        StaffMemberName $name,
        Email $email
    ) {
        $this->id = $id;
        $this->name = $name->getFirstName();
        $this->surname = $name->getSurname();
        $this->email = $email;
        $this->status = self::STATUS_CREATED;
        $this->roles = [RoleEnum::SCHOOL_USER->value];
        $this->schoolId = $schoolId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function invite(InvitationToken $token): self
    {
        if ($this->status !== self::STATUS_CREATED) {
            throw new \DomainException('Can not be invited.');
        }
        $this->status = self::STATUS_INVITED;
        $this->invitationToken = $token;

        return $this;
    }

    public function confirmAccount(string $passwordHash): self
    {
        if ($this->status !== self::STATUS_INVITED) {
            throw new \DomainException('Can not be confirmed.');
        }
        if ($this->invitationToken->isExpired()) {
            throw new \DomainException('Token is expired, please ask for a new one.');
        }
        $this->invitationToken = null;
        $this->status = self::STATUS_ACTIVE;
        $this->passwordHash = $passwordHash;

        return $this;
    }

    public function getId(): StaffMemberId
    {
        return $this->id;
    }

    public function changeRole(RoleEnum $role): self
    {
        $this->roles = [$role->value];

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): ?string
    {
        return $this->passwordHash;
    }

    public function upgradePasswordHash(string $newHashedPassword): self
    {
        // todo research, delete maybe
        $this->passwordHash = $newHashedPassword;

        return $this;
    }

    public function isFromSchool(School $school): bool
    {
        return $this->schoolId->getValue() === $school->getId()->getValue();
    }
}
