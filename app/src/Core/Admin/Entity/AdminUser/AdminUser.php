<?php

declare(strict_types=1);

namespace App\Core\Admin\Entity\AdminUser;

use App\Core\Admin\Repository\AdminUserRepository;
use App\Core\School\Common\RoleEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminUserRepository::class)]
#[ORM\Table(name: 'admin_user')]
#[ORM\UniqueConstraint('uk_admin_user_email', ['email'])]
class AdminUser
{
    #[ORM\Column(type: AdminUserIdType::NAME)]
    #[ORM\Id()]
    private AdminUserId $id;

    #[ORM\Column(length: 180, unique: true, nullable: false)]
    private string $email;

    /** @var array<int, string> */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(nullable: false)]
    private string $passwordHash;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $surname = null;

    public function __construct(
        AdminUserId $id,
        string $email,
        string $passwordHash,
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->roles = [
            RoleEnum::ADMIN_USER->value,
        ];
    }

    public function getId(): AdminUserId
    {
        return $this->id;
    }

    public function getEmail(): ?string
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }
}
