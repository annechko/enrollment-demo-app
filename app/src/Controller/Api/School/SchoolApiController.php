<?php

declare(strict_types=1);

namespace App\Controller\Api\School;

use App\Controller\Api\AbstractJsonApiController;
use App\Core\Common\RegexEnum;
use App\Core\School\UseCase\Member;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/school')]
class SchoolApiController extends AbstractJsonApiController
{
    // todo rename
    #[Route('/{schoolId}/invitation/{invitationToken}', name: 'api_school_member_register',
        requirements: [
            'schoolId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
            'invitationToken' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
        ], methods: ['POST'])]
    public function memberRegister(
        Request $request,
        Member\Register\Handler $handler,
        string $schoolId,
        string $invitationToken,
    ): Response {
        // todo check if token expired or logged in with account or account verified - redirect.
        return $this->handleWithResponse(
            Member\Register\Command::class,
            $handler,
            $request,
            commandCallback: function (Member\Register\Command $command) use (
                $schoolId,
                $invitationToken
            ) {
                $command->schoolId = $schoolId;
                $command->invitationToken = $invitationToken;
            }
        );
    }
}
