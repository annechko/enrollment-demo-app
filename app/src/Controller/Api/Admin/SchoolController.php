<?php

declare(strict_types=1);

namespace App\Controller\Api\Admin;

use App\Controller\Api\AbstractApiController;
use App\Core\Common\DefaultUserEnum;
use App\Core\Common\RegexEnum;
use App\Core\School\Common\RoleEnum;
use App\Core\School\Entity\School\School;
use App\ReadModel\Admin\School\Filter;
use App\ReadModel\Admin\School\SchoolFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\InvalidArgumentException;

#[Route('/api/admin/school')]
class SchoolController extends AbstractApiController
{
    private const MAX_ITEMS = 20;

    #[Route('', name: 'api_admin_school_list')]
    public function list(Request $request, SchoolFetcher $fetcher): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::ADMIN_USER->value);

        $filter = new Filter\Filter();

        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $fetcher->fetch(
            $filter,
            $request->query->getInt('page', 1),
            self::MAX_ITEMS,
            $request->query->get('sort', 'id'),
            $request->query->get('direction', 'desc')
        );

        $result = [];
        foreach ($pagination->getItems() as $school) {
            $result[] = [
                'id' => $school['id'],
                'adminId' => $school['admin_id'],
                'name' => $school['name'],
                'status' => $school['status'],
                'email' => $school['admin_email'],
                'invitationDate' => $school['invitation_date'],
                'createdAt' => $school['created_at'],
                'canBeConfirmed' => $school['status'] === School::STATUS_NEW,
                'canBeDeleted' => !DefaultUserEnum::isDefaultSchoolUser($school['admin_email']),
                // todo move logic to ValueObjects
            ];
        }

        return new JsonResponse($result);
    }

    #[Route('/{schoolId}/confirm', name: 'api_admin_school_confirm',
        requirements: ['schoolId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE],
        methods: ['POST']),
    ]
    public function confirm(
        string $schoolId,
        \App\Core\School\UseCase\School\Confirm\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::ADMIN_USER->value);

        $command = new \App\Core\School\UseCase\School\Confirm\Command($schoolId);
        try {
            $handler->handle($command);
        } catch (InvalidArgumentException|\DomainException $exception) {
            return new JsonResponse([
                'error' => $exception->getMessage(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new JsonResponse();
    }
}
