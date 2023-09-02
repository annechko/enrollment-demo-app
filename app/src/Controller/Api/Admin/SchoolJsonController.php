<?php

declare(strict_types=1);

namespace App\Controller\Api\Admin;

use App\Controller\Api\AbstractJsonApiController;
use App\Core\Common\RegexEnum;
use App\Core\School\Common\RoleEnum;
use App\Core\School\UseCase\School;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin/school')]
class SchoolJsonController extends AbstractJsonApiController
{
    #[Route('/{schoolId}/confirm', name: 'api_admin_school_confirm',
        requirements: ['schoolId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE],
        methods: ['POST']),
    ]
    public function confirm(
        string $schoolId,
        School\Confirm\Handler $handler,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::ADMIN_USER->value);
        return $this->handleWithResponse(
            School\Confirm\Command::class,
            $handler,
            $request
        );
    }

    #[Route('/{schoolId}/delete', name: 'api_admin_school_delete',
        requirements: ['schoolId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE],
        methods: ['POST']),
    ]
    public function deleteSchool(
        string $schoolId,
        School\Delete\Handler $handler,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::ADMIN_USER->value);

        return $this->handleWithResponse(
            School\Delete\Command::class,
            $handler,
            $request
        );
    }
}
