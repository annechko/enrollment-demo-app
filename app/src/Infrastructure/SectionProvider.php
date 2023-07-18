<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Security\AdminReadModel;
use App\Security\SchoolStaffMemberReadModel;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

class SectionProvider
{
    private const SECTION_ADMIN = 'admin';
    private const SECTION_SCHOOL = 'school';
    private const SECTIONS = [
        self::SECTION_ADMIN,
        self::SECTION_SCHOOL,
    ];
    private ?UserInterface $currentUser;
    private string $firewallName;
    private string $routeName;

    public function __construct(
        readonly Security $security,
        readonly RequestStack $request,
    ) {
        $this->currentUser = $security->getUser();
        $this->firewallName = $security->getFirewallConfig($request->getMainRequest())->getName();
        $this->routeName = $request->getMainRequest()->attributes->get('_route', '');
    }

    public function getCurrentSection(): ?string
    {
        if ($this->routeName === RouteEnum::HOME) {
            return null;
        }
        // todo maybe consider checking urls start with section name
        if ($this->currentUser === null) {
            if (in_array($this->firewallName, self::SECTIONS, true)) {
                return $this->firewallName;
            }

            return self::SECTION_ADMIN;
        }

        return match (get_class($this->currentUser)) {
            AdminReadModel::class => self::SECTION_ADMIN,
            SchoolStaffMemberReadModel::class => self::SECTION_SCHOOL,
            default => self::SECTION_ADMIN,
        };
    }
}
