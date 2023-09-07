<?php

declare(strict_types=1);

namespace App\Controller\Api\School;

use App\Controller\Api\AbstractJsonApiController;
use App\Core\Common\RegexEnum;
use App\Core\School\Common\RoleEnum;
use App\Core\School\Entity\Course\CourseId;
use App\Core\School\Entity\School\SchoolId;
use App\Core\School\Repository\CampusRepository;
use App\Core\School\Repository\CourseRepository;
use App\Core\School\Repository\SchoolRepository;
use App\Core\School\UseCase\Member;
use App\Core\School\UseCase\School;
use App\Security\School\SchoolStaffMemberReadModel;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/profile', name: 'api_school_profile_edit', methods: ['POST'])]
    public function profileEdit(
        Request $request,
        School\Profile\Edit\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_ADMIN->value);

        return $this->handleWithResponse(
            School\Profile\Edit\Command::class,
            $handler,
            $request,
            commandCallback: function (School\Profile\Edit\Command $command
            ) {
                $command->schoolId = $this->getCurrentUser()->schoolId;
                $command->staffMemberId = $this->getCurrentUser()->id;
            }
        );
    }

    #[Route('/campuses', name: 'api_school_campus_add', methods: ['POST'])]
    public function campusAdd(
        Request $request,
        School\Campus\Add\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_ADMIN->value);

        return $this->handleWithResponse(
            School\Campus\Add\Command::class,
            $handler,
            $request,
            commandCallback: function (School\Campus\Add\Command $command
            ) {
                $command->schoolId = $this->getCurrentUser()->schoolId;
            }
        );
    }

    #[Route('/campuses/{campusId}', name: 'api_school_campus_edit',
        requirements: ['campusId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE],
        methods: ['POST'])]
    public function campusEdit(
        Request $request,
        string $campusId,
        School\Campus\Edit\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_ADMIN->value);

        return $this->handleWithResponse(
            School\Campus\Edit\Command::class,
            $handler,
            $request,
            commandCallback: function (School\Campus\Edit\Command $command
            ) use ($campusId) {
                $command->campusId = $campusId;
            }
        );
    }

    #[Route('/profile', name: 'api_school_profile', methods: ['GET'])]
    public function profileGet(
        SchoolRepository $schoolRepository,
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_ADMIN->value);

        try {
            $school = $schoolRepository->get($this->getCurrentSchoolId());
        } catch (\Throwable $exception) {
            // todo add logs.
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'name' => $school->getName()->getValue(),
        ]);
    }

    #[Route('/campuses', name: 'api_school_campus_list', methods: ['GET'])]
    public function campusListGet(CampusRepository $repository): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $c = $repository->findAllOrderedByName($this->getCurrentSchoolId());
        $res = [];
        foreach ($c as $item) {
            $res[] = [
                'id' => $item->getId()->getValue(),
                'name' => $item->getName(),
                'address' => $item->getAddress(),
            ];
        }

        return new JsonResponse($res);
    }

    #[Route('/courses/{courseId}/intakes', name: 'api_school_course_intake_list',
        requirements: [
            'courseId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
        ],
        methods: ['GET'],
    )]
    public function intakeListGet(
        string $courseId,
        CourseRepository $repository,
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $c = $repository->get(new CourseId($courseId));
        $res = [];
        foreach ($c->getIntakes() as $intake) {
            $res[] = [
                'id' => $intake->getId()->getValue(),
                'name' => $intake->getName(),
                'classSize' => $intake->getClassSize(),
                'campus' => $intake->getCampus()?->getName(),
                'startDate' => $intake->getStartDate()->format('Y-m-d'),
                'endDate' => $intake->getEndDate()->format('Y-m-d'),
            ];
        }

        return new JsonResponse($res);
    }

    #[Route('/courses/{courseId}/intakes', name: 'api_school_course_intake_add',
        requirements: [
            'courseId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
        ],
        methods: ['POST'])]
    public function courseIntakeAdd(
        Request $request,
        string $courseId,
        School\Course\Intake\Add\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return $this->handleWithResponse(
            School\Course\Intake\Add\Command::class,
            $handler,
            $request
        );
    }

    private function getCurrentUser(): SchoolStaffMemberReadModel
    {
        if (!$this->getUser() instanceof SchoolStaffMemberReadModel) {
            throw new \LogicException();
        }

        return $this->getUser();
    }

    private function getCurrentSchoolId(): SchoolId
    {
        return new SchoolId($this->getCurrentUser()->schoolId);
    }
}
