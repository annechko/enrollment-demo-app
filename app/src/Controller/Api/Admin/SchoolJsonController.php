<?php

declare(strict_types=1);

namespace App\Controller\Api\Admin;

use App\Controller\Api\AbstractJsonApiController;
use App\Core\Common\RegexEnum;
use App\Core\School\Common\RoleEnum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin/school')]
class SchoolJsonController extends AbstractJsonApiController
{
    #[Route('/{schoolId}/delete', name: 'api_admin_school_delete',
        requirements: ['schoolId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE],
        methods: ['POST']),
    ]
    public function deleteSchool(
        string $schoolId,
        \App\Core\School\UseCase\School\Delete\Handler $handler,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::ADMIN_USER->value);

        return $this->handleWithResponse(
            \App\Core\School\UseCase\School\Delete\Command::class,
            $handler,
            $request
        );
    }
}
