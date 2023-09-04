<?php

declare(strict_types=1);

namespace App\Controller\Api\School;

use App\Controller\Api\AbstractApiController;
use App\Core\Common\NotFoundException;
use App\Core\Common\RegexEnum;
use App\Core\School\Common\RoleEnum;
use App\Core\School\Entity\Campus\CampusId;
use App\Core\School\Entity\Course\CourseId;
use App\Core\School\Entity\Course\Intake\IntakeId;
use App\Core\School\Entity\School\SchoolId;
use App\Core\School\Repository\CampusRepository;
use App\Core\School\Repository\CourseRepository;
use App\Core\School\Repository\SchoolRepository;
use App\Core\Student\Entity\Application\ApplicationId;
use App\Infrastructure\RouteEnum;
use App\ReadModel\School\ApplicationFetcher;
use App\Security\School\SchoolStaffMemberReadModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;
use Webmozart\Assert\InvalidArgumentException;

#[Route('/api/school')]
class SchoolController extends AbstractApiController
{


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

    #[Route('/applications', name: 'api_school_application_list', methods: ['GET'])]
    public function applicationList(
        ApplicationFetcher $fetcher
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_ADMIN->value);

        $applications = $fetcher->findAll($this->getCurrentSchoolId());
        $result = [];
        $format = 'M d, Y';
        foreach ($applications as $item) {
            $intake = [
                'startDate' => (new \DateTimeImmutable($item['intake_start_date']))->format(
                    $format
                ),
                'endDate' => (new \DateTimeImmutable($item['intake_end_date']))->format($format),
            ];
            $result[] = [
                'id' => $item['id'],
                'createdAt' => (new \DateTimeImmutable($item['created_at']))->format($format),
                'student' => [
                    'id' => $item['student_id'],
                    'name' => $item['student_name'],
                ],
                'course' => [
                    'id' => $item['course_id'],
                    'name' => $item['course_name'],
                ],
                'intake' => $intake,
                'status' => $item['status'],
            ];
        }

        return new JsonResponse($result);
    }

    #[Route('/applications/{applicationId}', name: 'api_school_application',
        requirements: ['applicationId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE],
        methods: ['GET'])]
    public function applicationGet(
        string $applicationId,
        ApplicationFetcher $fetcher
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $application = $fetcher->findOne(
            new ApplicationId($applicationId),
            $this->getCurrentSchoolId()
        );
        if (count($application) === 0) {
            return new JsonResponse([]);
        }
        $format = 'M d, Y';

        return new JsonResponse([
            'id' => $application['id'],
            'student' => [
                'id' => $application['id'],
                'passportNumber' => $application['passport_number'],
                'passportExpiry' => (new \DateTimeImmutable(
                    $application['passport_expiry']
                ))->format($format),
                'dateOfBirth' => (new \DateTimeImmutable($application['date_of_birth']))->format(
                    $format
                ),
                'fullName' => $application['full_name'],
                'preferredName' => $application['preferred_name'],
            ],
            'course' => [
                'id' => $application['id'],
            ],
            'intake' => [
                'id' => $application['id'],
            ],
            'createdAt' => (new \DateTimeImmutable($application['created_at']))->format($format),
        ]);
    }

    #[Route('/campuses/{applicationId}', name: 'api_school_application_edit',
        requirements: ['applicationId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE],
        methods: ['POST'])]
    public function applicationEdit(
        Request $request,
        string $applicationId,
        \App\Core\School\UseCase\School\Campus\Edit\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $command = new \App\Core\School\UseCase\School\Campus\Edit\Command($campusId);

        return $this->handleWithResponse(
            $command,
            \App\Core\School\UseCase\School\Campus\Edit\Form::class,
            $handler,
            $request
        );
    }

    #[Route('/campuses/{campusId}', name: 'api_school_campus_edit',
        requirements: ['campusId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE],
        methods: ['POST'])]
    public function campusEdit(
        Request $request,
        string $campusId,
        \App\Core\School\UseCase\School\Campus\Edit\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $command = new \App\Core\School\UseCase\School\Campus\Edit\Command($campusId);

        return $this->handleWithResponse(
            $command,
            \App\Core\School\UseCase\School\Campus\Edit\Form::class,
            $handler,
            $request
        );
    }

    #[Route('/campuses', name: 'api_school_campus_add', methods: ['POST'])]
    public function campusAdd(
        Request $request,
        \App\Core\School\UseCase\School\Campus\Add\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $command = new \App\Core\School\UseCase\School\Campus\Add\Command(
            $this->getCurrentSchoolId()->getValue()
        );

        return $this->handleWithResponse(
            $command,
            \App\Core\School\UseCase\School\Campus\Add\Form::class,
            $handler,
            $request
        );
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

    #[Route('/campuses/{campusId}', name: 'api_school_campus',
        requirements: ['campusId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE],
        methods: ['GET'])]
    public function campusGet(CampusRepository $repository, string $campusId): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        try {
            $campus = $repository->get(new CampusId($campusId));
        } catch (NotFoundException $exception) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $campus->getId()->getValue(),
            'name' => $campus->getName(),
            'address' => $campus->getAddress(),
        ]);
    }

    #[Route('/courses', name: 'api_school_course_list', methods: ['GET'])]
    public function courseListGet(CourseRepository $repository): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $courses = $repository->findAllOrderedByName($this->getCurrentSchoolId());
        $res = [];
        foreach ($courses as $course) {
            $res[] = [
                'id' => $course->getId()->getValue(),
                'name' => $course->getName(),
                'description' => $course->getDescription(),
            ];
        }

        return new JsonResponse($res);
    }

    #[Route('/courses', name: 'api_school_course_add', methods: ['POST'])]
    public function courseAdd(
        Request $request,
        \App\Core\School\UseCase\School\Course\Add\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $command = new \App\Core\School\UseCase\School\Course\Add\Command(
            $this->getCurrentSchoolId()->getValue()
        );

        return $this->handleWithResponse(
            $command,
            \App\Core\School\UseCase\School\Course\Add\Form::class,
            $handler,
            $request,
            fn (CourseId $result) => ['id' => $result->getValue()]
        );
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
        \App\Core\School\UseCase\School\Course\Intake\Add\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $command = new \App\Core\School\UseCase\School\Course\Intake\Add\Command($courseId);

        return $this->handleWithResponse(
            $command,
            \App\Core\School\UseCase\School\Course\Intake\Add\Form::class,
            $handler,
            $request,
        );
    }

    #[Route('/courses/{courseId}/intakes/{intakeId}', name: 'api_school_course_intake_edit',
        requirements: [
            'courseId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
            'intakeId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
        ],
        methods: ['POST'])]
    public function courseIntakeEdit(
        Request $request,
        string $courseId,
        string $intakeId,
        \App\Core\School\UseCase\School\Course\Intake\Edit\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $command = new \App\Core\School\UseCase\School\Course\Intake\Edit\Command(
            $intakeId,
            $courseId
        );

        return $this->handleWithResponse(
            $command,
            \App\Core\School\UseCase\School\Course\Intake\Edit\Form::class,
            $handler,
            $request,
        );
    }

    #[Route('/courses/{courseId}/intakes/{intakeId}', name: 'api_school_course_intake',
        requirements: [
            'courseId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
            'intakeId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
        ],
        methods: ['GET'])]
    public function courseIntakeGet(
        string $courseId,
        string $intakeId,
        CourseRepository $repository,
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);
        $course = $repository->get(new CourseId($courseId));
        $intake = $course->getIntake(new IntakeId($intakeId));
        $res = [
            'id' => $intake->getId()->getValue(),
            'name' => $intake->getName(),
            'classSize' => $intake->getClassSize(),
            'campus' => $intake->getCampus()?->getId()->getValue(),
            'startDate' => $intake->getStartDate()->format('Y-m-d'),
            'endDate' => $intake->getEndDate()->format('Y-m-d'),
        ];

        return new JsonResponse($res);
    }

    #[Route('/courses/{courseId}/intakes/{intakeId}', name: 'api_school_course_intake_remove',
        requirements: [
            'courseId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
            'intakeId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
        ],
        methods: ['DELETE'])]
    public function courseIntakeRemove(
        string $courseId,
        string $intakeId,
        \App\Core\School\UseCase\School\Course\Intake\Remove\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        try {
            $command = new \App\Core\School\UseCase\School\Course\Intake\Remove\Command(
                $intakeId,
                $courseId
            );
            $handler->handle($command);
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse([
                'error' => $exception->getMessage(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new JsonResponse();
    }

    #[Route('/courses/courseData', name: 'api_school_course', methods: ['GET'])]
    public function courseGet(
        CourseRepository $repository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $result = ['course' => null];

        $courseId = $request->get('courseId', null);
        if ($courseId !== null) {
            $validator = Validation::createValidator();
            $violations = $validator->validate($courseId, [
                new Constraints\Regex(RegexEnum::UUID_PATTERN_REG_EXP),
            ]);
            if (count($violations) > 0) {
                $errors = [];
                foreach ($violations as $violation) {
                    $errors[] = $violation->getMessage();
                }

                return new JsonResponse(['error' => implode(';', $errors)],
                    Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            try {
                $course = $repository->get(new CourseId($courseId));
            } catch (NotFoundException $e) {
                return new JsonResponse([], Response::HTTP_NOT_FOUND);
            }

            $result['course']['id'] = $course->getId()->getValue();
            $result['course']['name'] = $course->getName();
            $result['course']['description'] = $course->getDescription();
        }

        return new JsonResponse($result);
    }

    #[Route('/courses/{courseId}', name: 'api_school_course_edit',
        requirements: [
            'courseId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
        ])]
    public function courseEdit(
        Request $request,
        string $courseId,
        \App\Core\School\UseCase\School\Course\Edit\Handler $handler,
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $command = new \App\Core\School\UseCase\School\Course\Edit\Command($courseId);

        return $this->handleWithResponse(
            $command,
            \App\Core\School\UseCase\School\Course\Edit\Form::class,
            $handler,
            $request
        );
    }

    #[Route('/sidebar', name: 'api_school_sidebar', methods: ['GET'])]
    public function getSidebar(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return new JsonResponse([
            [
                'title' => 'Dashboard',
                'to' => $this->generateUrl(RouteEnum::SCHOOL_HOME),
                'type' => 'home',
            ],
            [
                'title' => 'Courses',
                'to' => $this->generateUrl('school_course_list_show'),
                'type' => 'courses',
            ],
            [
                'title' => 'Campuses',
                'to' => $this->generateUrl('school_campus_list_show'),
                'type' => 'campuses',
            ],
            [
                'title' => 'Applications',
                'to' => $this->generateUrl('school_application_list_show'),
                'type' => 'application',
            ],
            [
                'title' => 'Students',
                'to' => $this->generateUrl('school_student_list_show'),
                'type' => 'students',
            ],
            [
                'title' => 'School profile',
                'to' => $this->generateUrl(RouteEnum::SCHOOL_PROFILE),
                'type' => 'settings',
            ],
        ]);
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
