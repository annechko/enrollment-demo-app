<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Security\AdminReadModel;
use App\Security\SchoolStaffMemberReadModel;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class SectionProvider
{
    private ?UserInterface $currentUser;

    public function __construct(
        readonly Security $security,
    ) {
        $this->currentUser = $security->getUser();
    }

    public function getCurrentSection(): ?string
    {
        // todo better decide based on current firewall
        if ($this->currentUser === null) {
            return null;
        }
        return match (get_class($this->currentUser)) {
            AdminReadModel::class => 'admin',
            SchoolStaffMemberReadModel::class => 'school',
            default => 'admin',
        };
    }
}
